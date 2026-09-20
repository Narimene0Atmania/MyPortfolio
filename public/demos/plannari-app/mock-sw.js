// Mock API for the Plannari portfolio demo. Intercepts every request the
// Flutter app makes to its hardcoded backend URL (http://127.0.0.1:8000/api/…
// — see ApiClient.defaultBaseUrl) and answers from an in-memory store instead,
// so the whole demo runs with zero real backend. State lives only in this
// worker's memory: it behaves correctly for the length of a browsing session,
// and resets on a hard reload / new visit, same as any other demo data.

const JSON_HEADERS = { 'Content-Type': 'application/json' };

function json(data, status = 200) {
  if (status === 204) return new Response(null, { status: 204 });
  return new Response(JSON.stringify(data), { status, headers: JSON_HEADERS });
}

function errorJson(message, status = 404) {
  return json({ message }, status);
}

// ---------------------------------------------------------------- dates

// local date components, not toISOString() — that forces UTC, and the app
// computes "today" in local time, so a demo visitor west of UTC would get
// yesterday's data for "today" and vice versa east of it
function fmt(date) {
  const y = date.getFullYear();
  const m = String(date.getMonth() + 1).padStart(2, '0');
  const d = String(date.getDate()).padStart(2, '0');
  return `${y}-${m}-${d}`;
}

function offsetDate(days) {
  const d = new Date();
  d.setHours(0, 0, 0, 0);
  d.setDate(d.getDate() + days);
  return fmt(d);
}

const TODAY = offsetDate(0);

// ------------------------------------------------------------------ store

let nextId = 1000;
const id = () => nextId++;

const store = {
  categories: [
    { id: 1, name: 'Study', color_index: 0, tasks: [
      { id: 1, category_id: 1, title: 'Review lecture notes', reusable: true },
      { id: 2, category_id: 1, title: 'Read a chapter', reusable: true },
    ] },
    { id: 2, name: 'Hobbies', color_index: 1, tasks: [
      { id: 3, category_id: 2, title: 'Sketch for 20 min', reusable: true },
      { id: 4, category_id: 2, title: 'Practice guitar', reusable: true },
    ] },
    { id: 3, name: 'Sports', color_index: 2, tasks: [
      { id: 5, category_id: 3, title: 'Stretch routine', reusable: true },
    ] },
  ],
  routines: [
    { id: 1, name: 'Gym', items: [
      { id: 1, title: 'Warm-up', duration: 10 },
      { id: 2, title: 'Lift', duration: 40 },
      { id: 3, title: 'Stretch', duration: 10 },
    ] },
  ],
  blocks: [
    { id: 1, name: 'Lunch', time: '12:30' },
    { id: 2, name: 'Dinner', time: '19:30' },
  ],
  notes: [
    { id: 1, title: 'Book flight', body: 'Check prices again before Friday.', color_index: 0, image_url: null },
    { id: 2, title: '', body: 'Ask Sam about the routine cascade thing', color_index: 2, image_url: null },
  ],
  reminders: [
    { id: 1, title: 'Drink water', time: '10:00', weekdays: [1, 2, 3, 4, 5], enabled: true },
    { id: 2, title: 'Stretch before bed', time: '22:00', weekdays: [], enabled: true },
  ],
  unsorted: [
    { id: 1, title: 'Call the dentist' },
  ],
  days: {
    [offsetDate(-2)]: [
      { id: 1, kind: 'block', title: 'Lunch', time: '12:30', duration: null, category_id: null, done: false, position: 0 },
      { id: 2, kind: 'task', title: 'Read a chapter', time: '09:00', duration: 30, category_id: 1, done: true, position: 1 },
      { id: 3, kind: 'task', title: 'Reply to emails', time: null, duration: null, category_id: null, done: true, position: 2 },
    ],
    [offsetDate(-1)]: [
      { id: 4, kind: 'block', title: 'Lunch', time: '12:30', duration: null, category_id: null, done: false, position: 0 },
      { id: 5, kind: 'block', title: 'Dinner', time: '19:30', duration: null, category_id: null, done: false, position: 1 },
      { id: 6, kind: 'task', title: 'Stretch routine', time: '08:00', duration: 15, category_id: 3, done: true, position: 2 },
      // left unchecked on purpose — this is what makes it a debt today
      { id: 7, kind: 'task', title: 'Pay internet bill', time: null, duration: null, category_id: null, done: false, position: 3 },
    ],
    [TODAY]: [
      { id: 8, kind: 'block', title: 'Lunch', time: '12:30', duration: null, category_id: null, done: false, position: 0 },
      { id: 9, kind: 'task', title: 'Warm-up', time: '07:00', duration: 10, category_id: 3, done: true, position: 1 },
      { id: 10, kind: 'task', title: 'Lift', time: '07:10', duration: 40, category_id: 3, done: true, position: 2 },
      { id: 11, kind: 'task', title: 'Stretch', time: '07:50', duration: 10, category_id: 3, done: true, position: 3 },
      { id: 12, kind: 'task', title: 'Review lecture notes', time: '09:00', duration: 45, category_id: 1, done: true, position: 4 },
      { id: 13, kind: 'task', title: 'Sketch for 20 min', time: null, duration: null, category_id: 2, done: false, position: 5 },
      { id: 14, kind: 'task', title: 'Water the plants', time: null, duration: null, category_id: null, done: false, position: 6 },
    ],
    [offsetDate(1)]: [
      { id: 15, kind: 'block', title: 'Dinner', time: '19:30', duration: null, category_id: null, done: false, position: 0 },
      { id: 16, kind: 'task', title: 'Practice guitar', time: '18:00', duration: 30, category_id: 2, done: false, position: 1 },
    ],
  },
};

function dayItems(date) {
  if (!store.days[date]) store.days[date] = [];
  return store.days[date];
}

function findItemLocation(itemId) {
  for (const date of Object.keys(store.days)) {
    const items = store.days[date];
    const item = items.find((i) => i.id === itemId);
    if (item) return { date, items, item };
  }
  return null;
}

// today's date isn't a debt candidate — only strictly-past days are
function computeDebts() {
  const debts = [];
  for (const date of Object.keys(store.days)) {
    if (date >= TODAY) continue;
    for (const item of store.days[date]) {
      if (item.kind === 'task' && !item.done) {
        debts.push({ id: item.id, title: item.title, from_date: date, category_id: item.category_id });
      }
    }
  }
  return debts;
}

// ----------------------------------------------------------------- routes

const routes = [
  ['GET', /^\/ping$/, () => json({ app: 'plannari', ok: true })],

  ['GET', /^\/categories$/, () => json(store.categories)],
  ['POST', /^\/categories$/, (m, body) => {
    const cat = { id: id(), name: body.name, color_index: body.color_index ?? 0, tasks: [] };
    store.categories.push(cat);
    return json(cat, 201);
  }],
  ['PATCH', /^\/categories\/(\d+)$/, (m, body) => {
    const cat = store.categories.find((c) => c.id === Number(m[1]));
    if (!cat) return errorJson('category not found');
    if (body.name != null) cat.name = body.name;
    if (body.color_index != null) cat.color_index = body.color_index;
    return json(cat);
  }],
  ['DELETE', /^\/categories\/(\d+)$/, (m) => {
    store.categories = store.categories.filter((c) => c.id !== Number(m[1]));
    return json(null, 204);
  }],
  ['GET', /^\/categories\/(\d+)\/schedule$/, () => json([])],
  ['POST', /^\/categories\/(\d+)\/tasks$/, (m, body) => {
    const cat = store.categories.find((c) => c.id === Number(m[1]));
    const task = { id: id(), category_id: Number(m[1]), title: body.title, reusable: body.reusable ?? true };
    cat?.tasks.push(task);
    return json(task, 201);
  }],
  ['PATCH', /^\/category-tasks\/(\d+)$/, (m, body) => {
    for (const cat of store.categories) {
      const task = cat.tasks.find((t) => t.id === Number(m[1]));
      if (task) {
        if (body.title != null) task.title = body.title;
        if (body.reusable != null) task.reusable = body.reusable;
        return json(task);
      }
    }
    return errorJson('task not found');
  }],
  ['DELETE', /^\/category-tasks\/(\d+)$/, (m) => {
    for (const cat of store.categories) cat.tasks = cat.tasks.filter((t) => t.id !== Number(m[1]));
    return json(null, 204);
  }],

  ['GET', /^\/routines$/, () => json(store.routines)],
  ['POST', /^\/routines$/, (m, body) => {
    const routine = { id: id(), name: body.name, items: (body.items || []).map((i) => ({ id: id(), ...i })) };
    store.routines.push(routine);
    return json(routine, 201);
  }],
  ['PATCH', /^\/routines\/(\d+)$/, (m, body) => {
    const routine = store.routines.find((r) => r.id === Number(m[1]));
    if (!routine) return errorJson('routine not found');
    if (body.name != null) routine.name = body.name;
    if (body.items != null) routine.items = body.items.map((i) => ({ id: i.id ?? id(), ...i }));
    return json(routine);
  }],
  ['DELETE', /^\/routines\/(\d+)$/, (m) => {
    store.routines = store.routines.filter((r) => r.id !== Number(m[1]));
    return json(null, 204);
  }],

  ['GET', /^\/blocks$/, () => json(store.blocks)],
  ['POST', /^\/blocks$/, (m, body) => {
    const block = { id: id(), name: body.name, time: body.time ?? null };
    store.blocks.push(block);
    return json(block, 201);
  }],
  ['PATCH', /^\/blocks\/(\d+)$/, (m, body) => {
    const block = store.blocks.find((b) => b.id === Number(m[1]));
    if (!block) return errorJson('block not found');
    if (body.name != null) block.name = body.name;
    if ('time' in body) block.time = body.time;
    return json(block);
  }],
  ['DELETE', /^\/blocks\/(\d+)$/, (m) => {
    store.blocks = store.blocks.filter((b) => b.id !== Number(m[1]));
    return json(null, 204);
  }],

  ['GET', /^\/days$/, (m, body, url) => {
    const from = url.searchParams.get('from');
    const to = url.searchParams.get('to');
    const out = {};
    if (from && to) {
      // new Date("YYYY-MM-DD") parses as UTC midnight, not local — build a
      // local date from the parts instead, same reason as fmt() above
      const [fy, fm, fd] = from.split('-').map(Number);
      for (let d = new Date(fy, fm - 1, fd); fmt(d) <= to; d.setDate(d.getDate() + 1)) {
        out[fmt(d)] = dayItems(fmt(d));
      }
    }
    return json(out);
  }],
  ['GET', /^\/days\/(\d{4}-\d{2}-\d{2})$/, (m) => json({ date: m[1], items: dayItems(m[1]) })],
  ['POST', /^\/days\/(\d{4}-\d{2}-\d{2})\/items$/, (m, body) => {
    const items = dayItems(m[1]);
    const item = {
      id: id(),
      kind: body.kind ?? 'task',
      title: body.title ?? '',
      time: body.time ?? null,
      duration: body.duration ?? null,
      category_id: body.category_id ?? null,
      done: false,
      position: items.length,
    };
    items.push(item);
    return json(item, 201);
  }],
  ['POST', /^\/days\/(\d{4}-\d{2}-\d{2})\/items\/bulk$/, (m, body) => {
    const items = dayItems(m[1]);
    for (const raw of body.items || []) {
      items.push({
        id: id(),
        kind: raw.kind ?? 'task',
        title: raw.title ?? '',
        time: raw.time ?? null,
        duration: raw.duration ?? null,
        category_id: raw.category_id ?? null,
        done: false,
        position: items.length,
      });
    }
    return json({ date: m[1], items });
  }],
  ['POST', /^\/days\/(\d{4}-\d{2}-\d{2})\/reorder$/, (m, body) => {
    const items = dayItems(m[1]);
    const byId = new Map(items.map((i) => [i.id, i]));
    const reordered = (body.ids || []).map((itemId, index) => {
      const it = byId.get(itemId);
      if (it) it.position = index;
      return it;
    }).filter(Boolean);
    store.days[m[1]] = reordered.length ? reordered : items;
    return json({ date: m[1], items: store.days[m[1]] });
  }],
  ['PATCH', /^\/day-items\/(\d+)$/, (m, body) => {
    const loc = findItemLocation(Number(m[1]));
    if (!loc) return errorJson('item not found');
    const { item } = loc;
    if (body.title != null) item.title = body.title;
    if ('time' in body) item.time = body.time;
    if ('duration' in body) item.duration = body.duration;
    if ('category_id' in body) item.category_id = body.category_id;
    if (body.done != null) item.done = body.done;
    return json(item);
  }],
  ['DELETE', /^\/day-items\/(\d+)$/, (m) => {
    const loc = findItemLocation(Number(m[1]));
    if (loc) loc.items.splice(loc.items.indexOf(loc.item), 1);
    return json(null, 204);
  }],
  ['POST', /^\/day-items\/delete$/, (m, body) => {
    for (const itemId of body.ids || []) {
      const loc = findItemLocation(itemId);
      if (loc) loc.items.splice(loc.items.indexOf(loc.item), 1);
    }
    return json(null, 204);
  }],
  ['POST', /^\/day-items\/(\d+)\/move$/, (m, body) => {
    const loc = findItemLocation(Number(m[1]));
    if (!loc) return errorJson('item not found');
    loc.items.splice(loc.items.indexOf(loc.item), 1);
    const moved = { ...loc.item, time: body.keep_time ? loc.item.time : null };
    dayItems(body.date).push(moved);
    return json(moved);
  }],

  ['GET', /^\/debts$/, () => json(computeDebts())],
  ['POST', /^\/debts\/(\d+)\/today$/, (m) => {
    const loc = findItemLocation(Number(m[1]));
    if (!loc) return errorJson('debt not found');
    loc.items.splice(loc.items.indexOf(loc.item), 1);
    const moved = { ...loc.item, time: null, position: dayItems(TODAY).length };
    dayItems(TODAY).push(moved);
    return json(null, 204);
  }],
  ['POST', /^\/debts\/(\d+)\/category$/, (m) => {
    const loc = findItemLocation(Number(m[1]));
    if (loc) loc.items.splice(loc.items.indexOf(loc.item), 1);
    return json(null, 204);
  }],

  ['GET', /^\/unsorted$/, () => json(store.unsorted)],
  ['POST', /^\/unsorted$/, (m, body) => {
    const item = { id: id(), title: body.title };
    store.unsorted.push(item);
    return json(item, 201);
  }],
  ['DELETE', /^\/unsorted\/(\d+)$/, (m) => {
    store.unsorted = store.unsorted.filter((u) => u.id !== Number(m[1]));
    return json(null, 204);
  }],
  ['POST', /^\/unsorted\/(\d+)\/place$/, (m, body) => {
    const it = store.unsorted.find((u) => u.id === Number(m[1]));
    if (!it) return errorJson('item not found');
    store.unsorted = store.unsorted.filter((u) => u.id !== it.id);
    const placed = { id: id(), kind: 'task', title: it.title, time: null, duration: null, category_id: null, done: false, position: dayItems(body.date).length };
    dayItems(body.date).push(placed);
    return json(placed);
  }],

  ['GET', /^\/notes$/, () => json(store.notes)],
  ['POST', /^\/notes$/, (m, body) => {
    const note = { id: id(), title: body.title ?? '', body: body.body ?? '', color_index: body.color_index ?? 0, image_url: null };
    store.notes.push(note);
    return json(note, 201);
  }],
  ['PATCH', /^\/notes\/(\d+)$/, (m, body) => {
    const note = store.notes.find((n) => n.id === Number(m[1]));
    if (!note) return errorJson('note not found');
    if (body.title != null) note.title = body.title;
    if (body.body != null) note.body = body.body;
    if (body.color_index != null) note.color_index = body.color_index;
    return json(note);
  }],
  ['DELETE', /^\/notes\/(\d+)$/, (m) => {
    store.notes = store.notes.filter((n) => n.id !== Number(m[1]));
    return json(null, 204);
  }],
  ['POST', /^\/notes\/(\d+)\/image$/, (m) => {
    // no real upload in a static demo — keep the note as-is rather than fail
    const note = store.notes.find((n) => n.id === Number(m[1]));
    return note ? json(note) : errorJson('note not found');
  }],
  ['DELETE', /^\/notes\/(\d+)\/image$/, (m) => {
    const note = store.notes.find((n) => n.id === Number(m[1]));
    if (note) note.image_url = null;
    return note ? json(note) : errorJson('note not found');
  }],

  ['GET', /^\/reminders$/, () => json(store.reminders)],
  ['POST', /^\/reminders$/, (m, body) => {
    const reminder = { id: id(), title: body.title, time: body.time, weekdays: body.weekdays ?? [], enabled: true };
    store.reminders.push(reminder);
    return json(reminder, 201);
  }],
  ['PATCH', /^\/reminders\/(\d+)$/, (m, body) => {
    const reminder = store.reminders.find((r) => r.id === Number(m[1]));
    if (!reminder) return errorJson('reminder not found');
    if (body.title != null) reminder.title = body.title;
    if (body.time != null) reminder.time = body.time;
    if (body.weekdays != null) reminder.weekdays = body.weekdays;
    if (body.enabled != null) reminder.enabled = body.enabled;
    return json(reminder);
  }],
  ['DELETE', /^\/reminders\/(\d+)$/, (m) => {
    store.reminders = store.reminders.filter((r) => r.id !== Number(m[1]));
    return json(null, 204);
  }],

  ['GET', /^\/stats$/, (m, body, url) => {
    const range = url.searchParams.get('range') || 'week';
    return json({
      range,
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      series: [
        { category_id: 1, name: 'Study', color_index: 0, planned: 5, done: 4, points: [100, 80, null, 100, 60, null, null] },
        { category_id: 2, name: 'Hobbies', color_index: 1, planned: 4, done: 2, points: [50, null, 100, null, 0, 100, null] },
        { category_id: 3, name: 'Sports', color_index: 2, planned: 6, done: 5, points: [100, 100, 80, null, 100, null, 60] },
      ],
      summary: { rate: 78, done: 11, planned: 15, best_category: 'Sports' },
    });
  }],
];

// -------------------------------------------------------------- fetch hook

self.addEventListener('install', (event) => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);
  if (!url.pathname.startsWith('/api/')) return; // let everything else hit the real network

  const path = url.pathname.slice(4); // strip leading "/api"
  const method = event.request.method;

  event.respondWith((async () => {
    let body = null;
    if (method !== 'GET' && method !== 'DELETE') {
      try { body = await event.request.clone().json(); } catch (e) { body = {}; }
    }

    for (const [routeMethod, pattern, handler] of routes) {
      if (routeMethod !== method) continue;
      const match = path.match(pattern);
      if (match) return handler(match, body, url);
    }

    return errorJson(`no mock route for ${method} ${path}`, 404);
  })());
});
