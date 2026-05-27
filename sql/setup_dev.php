<?php
/**
 * Local SQLite dev setup.
 * Run once from the project root:  php sql/setup_dev.php
 * Drops and recreates the database with seed data.
 */
define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/lib/config.php';
require_once APP_ROOT . '/lib/db.php';

$dbFile = APP_ROOT . '/storage/aatimes.db';

if (file_exists($dbFile)) {
    unlink($dbFile);
    echo "Dropped existing database.\n";
}

$pdo = db();
echo "Creating schema…\n";
$pdo->exec(file_get_contents(APP_ROOT . '/sql/create_sqlite.sql'));

echo "Seeding users…\n";
$insertUser = $pdo->prepare(
    'INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)'
);
$users = [
    ['Editor Admin',  'editor@aatimes.test',  password_hash('Editor#2026', PASSWORD_BCRYPT), 'editor'],
    ['Sagar Khanal',  'sagar@aatimes.test',   password_hash('Journo#2026', PASSWORD_BCRYPT), 'journalist'],
    ['Mahmud Didar',  'mahmud@aatimes.test',  password_hash('Journo#2026', PASSWORD_BCRYPT), 'journalist'],
    ['Subodh Gautam', 'subodh@aatimes.test',  password_hash('Journo#2026', PASSWORD_BCRYPT), 'journalist'],
];
foreach ($users as $u) $insertUser->execute($u);

echo "Seeding categories…\n";
$insertCat = $pdo->prepare('INSERT INTO categories (name, slug) VALUES (?, ?)');
$cats = [
    ['Australia',  'australia'],
    ['Asia',       'asia'],
    ['Pacific',    'pacific'],
    ['World',      'world'],
    ['Business',   'business'],
    ['Sport',      'sport'],
    ['Opinion',    'opinion'],
    ['Technology', 'technology'],
];
foreach ($cats as $c) $insertCat->execute($c);

echo "Seeding articles…\n";
$now = date('Y-m-d H:i:s');
$insertArt = $pdo->prepare(
    'INSERT INTO articles (title, slug, body, status, author_id, category_id, created_at, updated_at, published_at)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
);

$articles = [
    [
        'Darwin Port Expansion Signals New Era for Northern Australia Trade',
        'darwin-port-expansion-northern-trade',
        "The Federal Government has announced a \$2.4 billion investment to expand Darwin Harbour, positioning the Northern Territory as a gateway for Indo-Pacific trade. The project, expected to be complete by 2028, will increase container capacity threefold and create an estimated 3,500 direct jobs.\n\nMinister for Infrastructure Sarah Reynolds described the expansion as \"transformative for Australia's economic relationship with Asia.\" The port currently handles \$5.2 billion in goods annually, a figure projected to exceed \$18 billion by the decade's end.\n\nLocal businesses have welcomed the announcement, with the Darwin Chamber of Commerce noting that improved logistics will reduce freight costs by up to 22% for Northern Territory exporters.",
        'published', 2, 1, $now, $now, $now,
    ],
    [
        'ASEAN Summit Backs New Regional Digital Trade Framework',
        'asean-summit-digital-trade-framework',
        "Leaders from the ten ASEAN member states have endorsed a landmark Digital Trade Framework at the annual summit in Kuala Lumpur, paving the way for frictionless cross-border e-commerce across the region.\n\nThe framework establishes common standards for digital signatures, data localisation exceptions, and consumer protection. Analysts say the agreement could unlock USD 1 trillion in economic activity by 2030.\n\n\"This is the most significant economic integration step ASEAN has taken in a decade,\" said Secretary-General Kao Kim Hourn. Australia, as a Dialogue Partner, expressed support for the framework and signalled interest in eventual alignment.\n\nCritics, however, raised concerns about enforcement mechanisms and the absence of binding commitments on cybersecurity standards.",
        'published', 3, 2, $now, $now, $now,
    ],
    [
        'Pacific Island Leaders Demand Binding Climate Finance at COP31',
        'pacific-leaders-climate-finance-cop31',
        "A coalition of fourteen Pacific Island nations presented a unified position paper at COP31 in Brisbane calling for binding annual climate finance contributions of USD 400 billion from industrialised countries by 2030.\n\nThe Alliance of Small Island States (AOSIS) argues that existing voluntary pledge mechanisms have repeatedly fallen short. Tuvalu's Prime Minister Feleti Teo was blunt: \"Voluntary is a polite word for optional. Optional means our islands drown.\"\n\nAustralia pledged an additional AUD 500 million over four years toward Pacific climate adaptation, though Pacific leaders said the figure remained insufficient relative to historical emissions responsibilities.\n\nNegotiations are expected to continue through the final days of the two-week summit.",
        'published', 4, 3, $now, $now, $now,
    ],
    [
        'RBA Holds Rates Steady as Inflation Cools to 2.8 Per Cent',
        'rba-holds-rates-inflation-cools',
        "The Reserve Bank of Australia has kept the official cash rate unchanged at 3.85 per cent for a fourth consecutive meeting, citing encouraging progress on inflation which has eased to 2.8 per cent — within the central bank's 2–3 per cent target band for the first time in three years.\n\nGovernor Michele Bullock acknowledged the improvement but cautioned that the board remains watchful of persistent services inflation and a tight labour market. \"The last mile of disinflation is often the hardest,\" she noted in the post-meeting statement.\n\nFinancial markets currently price a 62 per cent probability of a rate cut at the August meeting, up from 41 per cent before today's decision.",
        'published', 2, 5, $now, $now, $now,
    ],
    [
        "Japan's Robotics Industry Eyes Australian Mining Partnership",
        'japan-robotics-australian-mining',
        "A delegation of fifteen Japanese robotics and automation companies visited the Pilbara region this week, exploring opportunities to deploy autonomous drilling and ore-sorting technology in partnership with Australian mining operators.\n\nThe visit, facilitated by the Australia-Japan Foundation, comes as Rio Tinto and BHP accelerate their autonomous haulage programs. Japanese firms including Komatsu and Hitachi already supply autonomous trucks, but the delegation was focused on next-generation underground mining robots capable of operating in higher-temperature environments.\n\nThe potential market is significant: Australia's mining sector spent AUD 6.8 billion on equipment in 2025, and analysts estimate that full automation of underground operations could reduce operating costs by up to 35 per cent.",
        'pending', 3, 2, $now, $now, null,
    ],
    [
        'CDU Research Team Develops Saltwater-Resistant Mangrove Hybrid',
        'cdu-saltwater-mangrove-hybrid',
        "Scientists at Charles Darwin University have successfully cultivated a hybrid mangrove variety capable of surviving salinity levels 40 per cent higher than existing species, offering hope for coastal restoration in regions affected by sea-level rise.\n\nThe breakthrough, published in Nature Climate Change, involved cross-breeding Rhizophora stylosa with a heat-tolerant variety from the Persian Gulf. The hybrid has shown a 78 per cent survival rate in simulated 2100 sea-level conditions over an 18-month trial.\n\nLead researcher Dr Amara Singh said the team is now planning field trials in Tuvalu and the Maldives in collaboration with local environment agencies.",
        'draft', 4, 1, $now, $now, null,
    ],
];

foreach ($articles as $a) $insertArt->execute($a);

echo "Seeding comments…\n";
$insertCmt = $pdo->prepare(
    'INSERT INTO comments (article_id, author_name, body, status, created_at) VALUES (?, ?, ?, ?, ?)'
);
$comments = [
    [1, 'James Whitfield',  'Great investment — Northern Australia has been underutilised for too long.', 'approved', $now],
    [1, 'Priya Nair',       'Will be interesting to see how this affects freight costs to Southeast Asia.', 'approved', $now],
    [2, 'Tan Wei Lin',      'The enforcement gap is a real concern. Voluntary frameworks have failed before.', 'approved', $now],
    [2, 'Reza Ahmadi',      'Exciting news for regional startups — cross-border payments have been a nightmare.', 'pending', $now],
    [3, 'Kalani Akana',     "Tuvalu's PM said it perfectly. Voluntary = optional = inadequate.", 'approved', $now],
    [4, 'Marcus Chen',      'The 2.8% figure is good news. Hopefully rates come down in August.', 'approved', $now],
    [4, "Sarah O'Brien",    "Still concerned about housing affordability. Rate cuts won't help renters.", 'pending', $now],
];
foreach ($comments as $c) $insertCmt->execute($c);

echo "\nDone! Database ready at storage/aatimes.db\n";
echo "\nUser counts by role:\n";
foreach ($pdo->query("SELECT role, COUNT(*) AS n FROM users GROUP BY role") as $r) {
    echo "  {$r['role']}: {$r['n']}\n";
}
echo "\nArticle counts by status:\n";
foreach ($pdo->query("SELECT status, COUNT(*) AS n FROM articles GROUP BY status") as $r) {
    echo "  {$r['status']}: {$r['n']}\n";
}
echo "\nSeed accounts:\n";
echo "  editor@aatimes.test  / Editor#2026  (editor)\n";
echo "  sagar@aatimes.test   / Journo#2026  (journalist)\n";
echo "  mahmud@aatimes.test  / Journo#2026  (journalist)\n";
echo "  subodh@aatimes.test  / Journo#2026  (journalist)\n";
