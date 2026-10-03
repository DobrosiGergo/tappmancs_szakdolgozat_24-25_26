import { readFileSync, writeFileSync, mkdirSync } from 'node:fs';
import { basename, dirname, resolve } from 'node:path';

// Keyed by spec file name, not by full path, so moving a spec between folders
// does not silently break the labels shown on the /about page.
const AREAS = {
    'about.spec.ts': 'Tudj meg többet oldal',
    'login.spec.ts': 'Bejelentkezés',
    'registration.spec.ts': 'Regisztráció',
    'home.spec.ts': 'Nyitóoldal',
    'settings.spec.ts': 'Fiókbeállítások',
    'shelter-management.spec.ts': 'Menhelykezelés',
    'staffing.spec.ts': 'Munkatársak',
    'user-browsing.spec.ts': 'Böngészés felhasználóként',
    'worker-interactions.spec.ts': 'Munkatársi műveletek',
};

const [, , reportPath, outputPath] = process.argv;
if (!reportPath || !outputPath) {
    console.error('Usage: node export-results.mjs <playwright-report.json> <output.json>');
    process.exit(1);
}

const report = JSON.parse(readFileSync(reportPath, 'utf8'));

const areas = [];
const unmapped = [];
const browsers = new Set();
let runs = 0;
let passed = 0;

for (const file of report.suites) {
    const specs = (file.suites ?? []).flatMap(inner => inner.specs ?? []);
    const cases = new Set();

    for (const spec of specs) {
        for (const test of spec.tests) {
            browsers.add(test.projectName);
            runs += 1;
            if (test.status === 'expected') {
                passed += 1;
            }
        }
        cases.add(spec.title.replace(/\s*@\S+/g, '').trim());
    }

    if (!cases.size) {
        continue;
    }

    const area = AREAS[basename(file.file)];
    if (!area) {
        unmapped.push(file.file);
        continue;
    }

    areas.push({ area, cases: [...cases] });
}

if (unmapped.length) {
    console.error(`No Hungarian area name for: ${unmapped.join(', ')}\nAdd an entry to AREAS in scripts/export-results.mjs.`);
    process.exit(1);
}

const results = {
    startedAt: report.stats.startTime,
    durationSeconds: Math.round(report.stats.duration / 1000),
    browsers: [...browsers].sort(),
    scenarios: areas.reduce((total, area) => total + area.cases.length, 0),
    runs,
    passed,
    failed: runs - passed,
    areas,
};

mkdirSync(dirname(resolve(outputPath)), { recursive: true });
writeFileSync(resolve(outputPath), JSON.stringify(results, null, 4) + '\n');
console.log(`${results.passed}/${results.runs} passed across ${results.browsers.join(', ')} -> ${outputPath}`);
