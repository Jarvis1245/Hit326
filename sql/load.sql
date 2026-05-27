-- The Austro-Asian Times – seed data (MySQL/MariaDB)
-- Run AFTER create.sql
-- Passwords: Editor#2026 and Journo#2026 (bcrypt cost 12)

USE aatimes;

INSERT INTO users (name, email, password_hash, role) VALUES
  ('Editor Admin',   'editor@aatimes.test',  '$2y$12$x1YOfP4GfJeC5oD7AnPE0eTUDJLfbLI7aWCdsP8AmwU1IjFAAXs/q', 'editor'),
  ('Sagar Khanal',   'sagar@aatimes.test',   '$2y$12$UlEvqzHMhcXm9mw8CPGKLOkJ8x0O6aRRSSD5r2.IaQ.9cJ5TbLopC', 'journalist'),
  ('Mahmud Didar',   'mahmud@aatimes.test',  '$2y$12$UlEvqzHMhcXm9mw8CPGKLOkJ8x0O6aRRSSD5r2.IaQ.9cJ5TbLopC', 'journalist'),
  ('Subodh Gautam',  'subodh@aatimes.test',  '$2y$12$UlEvqzHMhcXm9mw8CPGKLOkJ8x0O6aRRSSD5r2.IaQ.9cJ5TbLopC', 'journalist');

INSERT INTO categories (name, slug) VALUES
  ('Australia',  'australia'),
  ('Asia',       'asia'),
  ('Pacific',    'pacific'),
  ('World',      'world'),
  ('Business',   'business'),
  ('Sport',      'sport'),
  ('Opinion',    'opinion'),
  ('Technology', 'technology');

INSERT INTO articles (title, slug, body, status, author_id, category_id, published_at) VALUES
  (
    'Darwin Port Expansion Signals New Era for Northern Australia Trade',
    'darwin-port-expansion-northern-trade',
    'The Federal Government has announced a $2.4 billion investment to expand Darwin Harbour, positioning the Northern Territory as a gateway for Indo-Pacific trade. The project, expected to be complete by 2028, will increase container capacity threefold and create an estimated 3,500 direct jobs.\n\nMinister for Infrastructure Sarah Reynolds described the expansion as "transformative for Australia''s economic relationship with Asia." The port currently handles $5.2 billion in goods annually, a figure projected to exceed $18 billion by the decade''s end.\n\nLocal businesses have welcomed the announcement, with the Darwin Chamber of Commerce noting that improved logistics will reduce freight costs by up to 22% for Northern Territory exporters.',
    'published', 2, 1, NOW()
  ),
  (
    'ASEAN Summit Backs New Regional Digital Trade Framework',
    'asean-summit-digital-trade-framework',
    'Leaders from the ten ASEAN member states have endorsed a landmark Digital Trade Framework at the annual summit in Kuala Lumpur, paving the way for frictionless cross-border e-commerce across the region.\n\nThe framework establishes common standards for digital signatures, data localisation exceptions, and consumer protection. Analysts say the agreement could unlock USD 1 trillion in economic activity by 2030.\n\n"This is the most significant economic integration step ASEAN has taken in a decade," said Secretary-General Kao Kim Hourn. Australia, as a Dialogue Partner, expressed support for the framework and signalled interest in eventual alignment.\n\nCritics, however, raised concerns about enforcement mechanisms and the absence of binding commitments on cybersecurity standards.',
    'published', 3, 2, NOW()
  ),
  (
    'Pacific Island Leaders Demand Binding Climate Finance at COP31',
    'pacific-leaders-climate-finance-cop31',
    'A coalition of fourteen Pacific Island nations presented a unified position paper at COP31 in Brisbane calling for binding annual climate finance contributions of USD 400 billion from industrialised countries by 2030.\n\nThe Alliance of Small Island States (AOSIS) argues that existing voluntary pledge mechanisms have repeatedly fallen short. Tuvalu''s Prime Minister Feleti Teo was blunt: "Voluntary is a polite word for optional. Optional means our islands drown."\n\nAustralia pledged an additional AUD 500 million over four years toward Pacific climate adaptation, though Pacific leaders said the figure remained insufficient relative to historical emissions responsibilities.\n\nNegotiations are expected to continue through the final days of the two-week summit.',
    'published', 4, 3, NOW()
  ),
  (
    'RBA Holds Rates Steady as Inflation Cools to 2.8 Per Cent',
    'rba-holds-rates-inflation-cools',
    'The Reserve Bank of Australia has kept the official cash rate unchanged at 3.85 per cent for a fourth consecutive meeting, citing encouraging progress on inflation which has eased to 2.8 per cent — within the central bank''s 2–3 per cent target band for the first time in three years.\n\nGovernor Michele Bullock acknowledged the improvement but cautioned that the board remains watchful of persistent services inflation and a tight labour market. "The last mile of disinflation is often the hardest," she noted in the post-meeting statement.\n\nFinancial markets currently price a 62 per cent probability of a rate cut at the August meeting, up from 41 per cent before today''s decision.',
    'published', 2, 5, NOW()
  ),
  (
    'Japan''s Robotics Industry Eyes Australian Mining Partnership',
    'japan-robotics-australian-mining',
    'A delegation of fifteen Japanese robotics and automation companies visited the Pilbara region this week, exploring opportunities to deploy autonomous drilling and ore-sorting technology in partnership with Australian mining operators.\n\nThe visit, facilitated by the Australia-Japan Foundation, comes as Rio Tinto and BHP accelerate their autonomous haulage programs. Japanese firms including Komatsu and Hitachi already supply autonomous trucks, but the delegation was focused on next-generation underground mining robots capable of operating in higher-temperature environments.\n\nThe potential market is significant: Australia''s mining sector spent AUD 6.8 billion on equipment in 2025, and analysts estimate that full automation of underground operations could reduce operating costs by up to 35 per cent.',
    'pending', 3, 2, NULL
  ),
  (
    'CDU Research Team Develops Saltwater-Resistant Mangrove Hybrid',
    'cdu-saltwater-mangrove-hybrid',
    'Scientists at Charles Darwin University have successfully cultivated a hybrid mangrove variety capable of surviving salinity levels 40 per cent higher than existing species, offering hope for coastal restoration in regions affected by sea-level rise.\n\nThe breakthrough, published in Nature Climate Change, involved cross-breeding Rhizophora stylosa with a heat-tolerant variety from the Persian Gulf. The hybrid has shown a 78 per cent survival rate in simulated 2100 sea-level conditions over an 18-month trial.\n\nLead researcher Dr Amara Singh said the team is now planning field trials in Tuvalu and the Maldives in collaboration with local environment agencies.',
    'draft', 4, 1, NULL
  );

INSERT INTO comments (article_id, author_name, body, status) VALUES
  (1, 'James Whitfield',  'Great investment — Northern Australia has been underutilised for too long.', 'approved'),
  (1, 'Priya Nair',       'Will be interesting to see how this affects freight costs to Southeast Asia.', 'approved'),
  (2, 'Tan Wei Lin',      'The enforcement gap is a real concern. Voluntary frameworks have failed before.', 'approved'),
  (2, 'Reza Ahmadi',      'Exciting news for regional startups — cross-border payments have been a nightmare.', 'pending'),
  (3, 'Kalani Akana',     'Tuvalu''s PM said it perfectly. Voluntary = optional = inadequate.', 'approved'),
  (4, 'Marcus Chen',      'The 2.8% figure is good news. Hopefully rates come down in August.', 'approved'),
  (4, 'Sarah O''Brien',   'Still concerned about housing affordability. Rate cuts won''t help renters.', 'pending');
