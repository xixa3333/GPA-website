import test from "node:test";
import assert from "node:assert/strict";
import { courseRepository, settingsRepository, type Database } from "../lib/repositories.ts";

class FakeStatement {
  values: unknown[] = [];
  private sql: string;
  private log: { sql: string; values: unknown[] }[];
  constructor(sql: string, log: { sql: string; values: unknown[] }[]) { this.sql = sql; this.log = log; }
  bind(...values: unknown[]) { this.values = values; this.log.push({ sql: this.sql, values }); return this; }
  async all() { return { results: [] }; }
  async first() { return this.sql.startsWith("SELECT") ? null : { id: 1 }; }
  async run() { return { meta: { changes: 1 } }; }
}
function fakeDb(log: { sql: string; values: unknown[] }[]): Database { return { prepare: sql => new FakeStatement(sql, log) }; }

test("repository scopes every course operation to authenticated owner", async () => {
  const log: { sql: string; values: unknown[] }[] = []; const repo = courseRepository(fakeDb(log));
  await repo.list("a@example.com"); await repo.remove("b@example.com", 7);
  assert.equal(log[0].values[0], "a@example.com");
  assert.deepEqual(log[1].values, [7, "b@example.com"]);
  assert.match(log[1].sql, /owner_email=\?/);
});

test("settings use owner as conflict and lookup boundary", async () => {
  const log: { sql: string; values: unknown[] }[] = []; const repo = settingsRepository(fakeDb(log));
  await repo.get("first@example.com");
  await repo.save("second@example.com", { gpaMethod: "NKUST", systemRequiredTarget: 1, systemElectiveTarget: 2, generalTarget: 3, commonRequiredTarget: 4, freeElectiveTarget: 5, totalTarget: 6 });
  assert.deepEqual(log.map(x => x.values[0]), ["first@example.com", "second@example.com"]);
  assert.match(log[1].sql, /ON CONFLICT\(owner_email\)/);
});
