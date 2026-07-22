import test from "node:test";
import assert from "node:assert/strict";
import { readFile, readdir } from "node:fs/promises";
import { join } from "node:path";
import { fileURLToPath } from "node:url";

async function files(dir) { return (await readdir(dir, { withFileTypes: true })).flatMap(e => e.name === "node_modules" || e.name === "dist" ? [] : e.isDirectory() ? [files(join(dir, e.name))] : [join(dir, e.name)]); }
async function flatten(items) { const out = []; for (const item of items) out.push(...Array.isArray(item) ? await flatten(item) : item instanceof Promise ? await flatten([await item]) : [item]); return out; }

test("source contains no Gmail app-password or committed secret patterns", async () => {
  const paths = await flatten(await files(fileURLToPath(new URL("..", import.meta.url))));
  for (const path of paths) {
    const text = await readFile(path, "utf8").catch(() => "");
    assert.doesNotMatch(text, /(?:GMAIL|SMTP)_(?:PASSWORD|PASS)\s*=\s*[^\s$]/i, path);
    assert.doesNotMatch(text, /AIza[0-9A-Za-z_-]{30,}|gh[opsu]_[0-9A-Za-z]{30,}/, path);
  }
});
