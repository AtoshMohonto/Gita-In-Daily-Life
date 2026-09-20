-- Gita in Daily Life — demo seed data (Phase 1)
-- Run after schema.sql. Content-integrity note: verses/mantras below use
-- public-domain traditional Sanskrit sources with an original English
-- rendering prepared for this platform. Ashtavakra Gita and Avadhuta Gita
-- are seeded with a single well-known illustrative verse each, explicitly
-- marked as sample/demo content pending fuller scholarly-reviewed sourcing
-- (per spec §44/§45/§64) — do not treat as a complete or citable edition.

USE gita_in_daily_life;

-- ------------------------------------------------------------------ roles

INSERT INTO roles (name, slug, description) VALUES
('Super Admin', 'super_admin', 'Full access: content, users, roles, settings.'),
('Admin', 'admin', 'Manages all content and site settings.'),
('Editor', 'editor', 'Creates and publishes content.'),
('Contributor', 'contributor', 'Drafts content for review before publishing.'),
('Registered User', 'registered_user', 'Public account: bookmarks, reflections.');

INSERT INTO permissions (name, slug) VALUES
('Manage Settings', 'manage_settings'),
('Manage Users', 'manage_users'),
('Manage Content', 'manage_content'),
('Publish Content', 'publish_content'),
('Draft Content', 'draft_content');

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r JOIN permissions p ON 1=1 WHERE r.slug = 'super_admin';

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.slug IN ('manage_content','publish_content','draft_content')
WHERE r.slug = 'admin';

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.slug IN ('manage_content','publish_content')
WHERE r.slug = 'editor';

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.slug = 'draft_content'
WHERE r.slug = 'contributor';

-- Seeded Super Admin. Password: GitaAdmin@2026 — change after first login.
INSERT INTO users (name, username, email, password_hash, role_id, status)
VALUES (
    'Site Administrator',
    'admin',
    'callcenter.ovijat@gmail.com',
    '$2y$10$mIKkAQCyDypq/vbSX0V4lOjRm/IXikzyjHPHBDT.qQlzeOFYlcofe',
    (SELECT id FROM roles WHERE slug = 'super_admin'),
    'active'
);

-- ------------------------------------------------------------------ gitas

INSERT INTO gitas (name, slug, short_description, introduction, historical_context, philosophy, main_themes, author_attribution, language_available, featured, status)
VALUES
(
    'Bhagavad Gita', 'bhagavad-gita',
    'Counsel from Krishna to Arjuna on the battlefield of Kurukshetra: duty, action, detachment, and the eternal Self.',
    'The Bhagavad Gita is a dialogue between Krishna and the warrior Arjuna, set just before a great battle. Facing despair over fighting his own kin, Arjuna receives teaching on duty, right action, detachment from results, and the eternal nature of the Self.',
    'Traditionally part of the Mahabharata (Bhishma Parva). Dating is debated among scholars and is not settled here; this platform presents the teaching for study and reflection rather than asserting a specific historical timeline.',
    'Presents karma yoga (the path of action), jnana yoga (the path of knowledge), and bhakti yoga (the path of devotion) as complementary approaches to the same goal.',
    'Duty, detachment, the eternal Self, equanimity, devotion',
    'Traditionally attributed to the sage Vyasa',
    'en,bn', 1, 'published'
),
(
    'Ashtavakra Gita', 'ashtavakra-gita',
    'A dialogue between the sage Ashtavakra and King Janaka on the nature of the Self and liberation.',
    'The Ashtavakra Gita presents a direct, uncompromising teaching on non-dual Self-realization, given in response to King Janaka asking about knowledge, liberation, and dispassion.',
    'Sample and demonstration content for this platform. Full chapter and verse content is pending scholarly-reviewed sourcing before production use; only the well-known opening verse is included here.',
    'Advaita (non-dual) teaching: the Self is pure, free consciousness, distinct from the body and the mind.',
    'Self-knowledge, non-duality, liberation, freedom from identification with the body',
    'Traditionally attributed to the sage Ashtavakra',
    'en', 1, 'published'
),
(
    'Avadhuta Gita', 'avadhuta-gita',
    'Teachings attributed to the sage Dattatreya on non-dual realization beyond ritual and form.',
    'The Avadhuta Gita is a concise, intense expression of non-dual realization, traditionally attributed to Dattatreya.',
    'Sample and demonstration content for this platform. Included here as a brief illustrative excerpt (the text''s well-known opening verse) pending fuller scholarly-reviewed content before production use.',
    'Radical non-dualism: the Self is free, formless awareness, untouched by ritual, status, or circumstance.',
    'Non-duality, freedom, the nature of pure awareness',
    'Traditionally attributed to the sage Dattatreya',
    'en', 0, 'published'
);

-- --------------------------------------------------------------- chapters

INSERT INTO chapters (gita_id, chapter_number, name, sanskrit_name, translation_name, summary, main_ideas, status)
VALUES
(
    (SELECT id FROM gitas WHERE slug = 'bhagavad-gita'), 2,
    'Transcendental Knowledge', 'सांख्ययोगः', 'The Yoga of Knowledge',
    'Krishna begins his teaching to the grieving Arjuna, introducing the eternal Self, the nature of right action, and equanimity toward pleasure and pain, success and failure.',
    'The eternal Self; tolerance of dualities; action without attachment to results; steadiness of mind',
    'published'
),
(
    (SELECT id FROM gitas WHERE slug = 'bhagavad-gita'), 6,
    'The Yoga of Meditation', 'ध्यानयोगः', 'Self-Control',
    'Krishna describes the discipline of meditation and the importance of steadily elevating oneself through self-effort.',
    'Meditation; self-discipline; the mind as friend or enemy',
    'published'
),
(
    (SELECT id FROM gitas WHERE slug = 'ashtavakra-gita'), 1,
    'Instruction on the Self', 'आत्मज्ञानोपदेशः', 'Teaching on Self-Knowledge',
    'King Janaka asks the sage Ashtavakra how knowledge, liberation, and dispassion are attained; the sage begins his direct teaching on the nature of the Self.',
    'Self-knowledge; liberation; dispassion',
    'published'
),
(
    (SELECT id FROM gitas WHERE slug = 'avadhuta-gita'), 1,
    'Adoration of the Self', 'आत्मनिवेदनम्', 'Opening Invocation',
    'The text opens by describing how the inclination toward non-dual understanding arises in the wise by grace, freeing one from deep fear.',
    'Grace; non-duality; freedom from fear',
    'published'
);

-- ----------------------------------------------------------------- verses

INSERT INTO verses (gita_id, chapter_id, verse_number, sanskrit_text, transliteration, literal_translation, simple_translation_en, key_teaching, translator, featured, status)
VALUES
(
    (SELECT id FROM gitas WHERE slug = 'bhagavad-gita'),
    (SELECT id FROM chapters WHERE gita_id = (SELECT id FROM gitas WHERE slug = 'bhagavad-gita') AND chapter_number = 2),
    14,
    'मात्रास्पर्शास्तु कौन्तेय शीतोष्णसुखदुःखदाः। आगमापायिनोऽनित्यास्तांस्तितिक्षस्व भारत॥',
    'mātrā-sparśās tu kaunteya śītoṣṇa-sukha-duḥkha-dāḥ / āgamāpāyino ''nityās tāṁs titikṣasva bhārata',
    'Contacts of the senses, O son of Kunti, give rise to cold and heat, pleasure and pain; they come and go, being impermanent — endure them, O Bharata.',
    'Season-bound contact with the senses brings cold and heat, pleasure and pain. These come and go, they do not last — learn to endure them.',
    'Sensory experiences of pleasure and pain are temporary; equanimity is built by learning to tolerate them.',
    'Prepared for Gita in Daily Life',
    0, 'published'
),
(
    (SELECT id FROM gitas WHERE slug = 'bhagavad-gita'),
    (SELECT id FROM chapters WHERE gita_id = (SELECT id FROM gitas WHERE slug = 'bhagavad-gita') AND chapter_number = 2),
    20,
    'न जायते म्रियते वा कदाचिन्नायं भूत्वा भविता वा न भूयः। अजो नित्यः शाश्वतोऽयं पुराणो न हन्यते हन्यमाने शरीरे॥',
    'na jāyate mriyate vā kadācin nāyaṁ bhūtvā bhavitā vā na bhūyaḥ / ajo nityaḥ śāśvato ''yaṁ purāṇo na hanyate hanyamāne śarīre',
    'The Self is never born, nor does it ever die; nor having come into being will it ever cease to be. It is unborn, eternal, ever-existing, primeval; it is not killed when the body is killed.',
    'The Self is never born and never dies. It does not come into being and then cease. It is unborn, eternal, ever-existing, and primeval; it is not killed when the body is killed.',
    'The true Self is eternal and untouched by the death of the body — a perspective for facing grief and loss.',
    'Prepared for Gita in Daily Life',
    0, 'published'
),
(
    (SELECT id FROM gitas WHERE slug = 'bhagavad-gita'),
    (SELECT id FROM chapters WHERE gita_id = (SELECT id FROM gitas WHERE slug = 'bhagavad-gita') AND chapter_number = 2),
    47,
    'कर्मण्येवाधिकारस्ते मा फलेषु कदाचन। मा कर्मफलहेतुर्भूर्मा ते सङ्गोऽस्त्वकर्मणि॥',
    'karmaṇy-evādhikāras te mā phaleṣu kadācana / mā karma-phala-hetur bhūr mā te saṅgo ''stv akarmaṇi',
    'You have a right to action alone, never to its fruits; let not the fruits of action be your motive, nor let your attachment be to inaction.',
    'You have a right to perform your duty, but never to the fruits of your actions. Do not let the results of action be your motive, and never be attached to inaction either.',
    'Focus wholeheartedly on sincere effort; release the anxious grip on results.',
    'Prepared for Gita in Daily Life',
    1, 'published'
),
(
    (SELECT id FROM gitas WHERE slug = 'bhagavad-gita'),
    (SELECT id FROM chapters WHERE gita_id = (SELECT id FROM gitas WHERE slug = 'bhagavad-gita') AND chapter_number = 2),
    48,
    'योगस्थः कुरु कर्माणि सङ्गं त्यक्त्वा धनञ्जय। सिद्ध्यसिद्ध्योः समो भूत्वा समत्वं योग उच्यते॥',
    'yoga-sthaḥ kuru karmāṇi saṅgaṁ tyaktvā dhanañjaya / siddhy-asiddhyoḥ samo bhūtvā samatvaṁ yoga ucyate',
    'Steadfast in yoga, perform your duties, abandoning attachment, O Dhananjaya, remaining even-minded in success and failure; such evenness is called yoga.',
    'Established in yoga, perform your duties, O Dhananjaya, abandoning attachment, and remaining even-minded in success and failure. This evenness of mind is called yoga.',
    'Equanimity toward success and failure is itself the practice.',
    'Prepared for Gita in Daily Life',
    0, 'published'
),
(
    (SELECT id FROM gitas WHERE slug = 'bhagavad-gita'),
    (SELECT id FROM chapters WHERE gita_id = (SELECT id FROM gitas WHERE slug = 'bhagavad-gita') AND chapter_number = 2),
    62,
    'ध्यायतो विषयान्पुंसः सङ्गस्तेषूपजायते। सङ्गात्सञ्जायते कामः कामात्क्रोधोऽभिजायते॥',
    'dhyāyato viṣayān puṁsaḥ saṅgas teṣūpajāyate / saṅgāt sañjāyate kāmaḥ kāmāt krodho ''bhijāyate',
    'For a person dwelling on sense objects, attachment to them arises; from attachment, desire is born; from desire, anger arises.',
    'When a person dwells on sense objects, attachment to them develops. From attachment comes desire, and from desire, anger is born.',
    'Anger has a traceable root: dwelling on objects leads to attachment, then desire, then anger.',
    'Prepared for Gita in Daily Life',
    0, 'published'
),
(
    (SELECT id FROM gitas WHERE slug = 'bhagavad-gita'),
    (SELECT id FROM chapters WHERE gita_id = (SELECT id FROM gitas WHERE slug = 'bhagavad-gita') AND chapter_number = 2),
    63,
    'क्रोधाद्भवति सम्मोहः सम्मोहात्स्मृतिविभ्रमः। स्मृतिभ्रंशाद्बुद्धिनाशो बुद्धिनाशात्प्रणश्यति॥',
    'krodhād bhavati sammohaḥ sammohāt smṛti-vibhramaḥ / smṛti-bhraṁśād buddhi-nāśo buddhi-nāśāt praṇaśyati',
    'From anger comes delusion; from delusion, confusion of memory; from confusion of memory, the ruin of discrimination; from the ruin of discrimination, one perishes.',
    'From anger comes delusion, from delusion the loss of memory, from loss of memory the ruin of discrimination, and when discrimination is ruined, one falls.',
    'Anger left unmanaged cascades into confusion, forgetfulness and poor judgment — catch it early.',
    'Prepared for Gita in Daily Life',
    1, 'published'
),
(
    (SELECT id FROM gitas WHERE slug = 'bhagavad-gita'),
    (SELECT id FROM chapters WHERE gita_id = (SELECT id FROM gitas WHERE slug = 'bhagavad-gita') AND chapter_number = 6),
    5,
    'उद्धरेदात्मनात्मानं नात्मानमवसादयेत्। आत्मैव ह्यात्मनो बन्धुरात्मैव रिपुरात्मनः॥',
    'uddhared ātmanātmānaṁ nātmānam avasādayet / ātmaiva hy ātmano bandhur ātmaiva ripur ātmanaḥ',
    'Let one lift oneself by oneself; let one not degrade oneself. For the self alone is the friend of the self, and the self alone is the enemy of the self.',
    'Lift yourself up by your own efforts; do not let yourself down. The self alone is the friend of the self, and the self alone is the enemy of the self.',
    'Self-discipline is self-authored — you are both your greatest support and your greatest obstacle.',
    'Prepared for Gita in Daily Life',
    0, 'published'
),
(
    (SELECT id FROM gitas WHERE slug = 'ashtavakra-gita'),
    (SELECT id FROM chapters WHERE gita_id = (SELECT id FROM gitas WHERE slug = 'ashtavakra-gita') AND chapter_number = 1),
    1,
    'जनक उवाच। कथं ज्ञानमवाप्नोति कथं मुक्तिर्भविष्यति। वैराग्यं च कथं प्राप्तमेतद्ब्रूहि मम प्रभो॥',
    'janaka uvāca: kathaṁ jñānam avāpnoti kathaṁ muktir bhaviṣyati / vairāgyaṁ ca kathaṁ prāptam etad brūhi mama prabho',
    'Janaka said: How does one attain knowledge? How does liberation come about? And how is dispassion reached? Tell me this, O Lord.',
    'King Janaka asked: How does one attain knowledge? How does liberation come about? And how is dispassion reached? Please tell me this.',
    'The dialogue opens with the three questions that frame the whole text: knowledge, liberation, and dispassion. Sample/demo verse — see the Gita introduction for sourcing notes.',
    'Prepared for Gita in Daily Life (sample content)',
    0, 'published'
),
(
    (SELECT id FROM gitas WHERE slug = 'avadhuta-gita'),
    (SELECT id FROM chapters WHERE gita_id = (SELECT id FROM gitas WHERE slug = 'avadhuta-gita') AND chapter_number = 1),
    1,
    'ॐ ईश्वरानुग्रहादेव पुंसामद्वैतवासना। महद्भयपरित्राणाद्विप्राणामुपजायते॥',
    'oṁ īśvarānugrahād eva puṁsām advaita-vāsanā / mahad-bhaya-paritrāṇād viprāṇām upajāyate',
    'By the grace of the Divine alone does the inclination toward non-duality arise in the wise, for the removal of great fear.',
    'By the grace of the Divine alone does the inclination toward non-duality arise in the wise, offering freedom from deep fear.',
    'Non-dual understanding is described here as arising by grace. Sample/demo verse — see the Gita introduction for sourcing notes.',
    'Prepared for Gita in Daily Life (sample content)',
    0, 'published'
);

-- ----------------------------------------------------------------- topics

INSERT INTO topics (name, slug, description, status) VALUES
('Karma', 'karma', 'Action, and the relationship between effort and outcome.', 'published'),
('Detachment', 'detachment', 'Releasing anxious attachment to results while remaining fully engaged in effort.', 'published'),
('Anger', 'anger', 'The roots of anger and how it clouds judgment.', 'published'),
('Self / Atman', 'self-atman', 'The nature of the true Self, beyond body and mind.', 'published'),
('Discipline', 'discipline', 'Self-effort and steady self-mastery.', 'published'),
('Grief', 'grief', 'Facing loss and impermanence.', 'published'),
('Dharma', 'dharma', 'Duty, right action, and ethical responsibility.', 'published');

-- ------------------------------------------------------------- situations

INSERT INTO situations (name, slug, description, icon, status) VALUES
('I am afraid of failing', 'fear-of-failure', 'Fear of an upcoming exam, attempt, or important outcome.', 'hourglass-split', 'published'),
('I feel angry', 'anger', 'Quick-triggered anger and its aftermath.', 'fire', 'published'),
('I am grieving a loss', 'grief-loss', 'Coping with the loss of someone or something important.', 'heart', 'published'),
('I lack discipline', 'lack-of-discipline', 'Struggling with consistency and self-control.', 'bullseye', 'published'),
('I feel stressed or anxious', 'stress', 'General stress, overwhelm, and restlessness.', 'wind', 'published');

-- --------------------------------------------------------------- mantras

INSERT INTO mantras (title, slug, sanskrit, transliteration, meaning, source, purpose, traditionally_recited, status)
VALUES
(
    'Pavamana Mantra — Lead Me From Untruth to Truth', 'om-asato-ma-sadgamaya',
    'ॐ असतो मा सद्गमय। तमसो मा ज्योतिर्गमय। मृत्योर्मा अमृतं गमय॥ ॐ शान्तिः शान्तिः शान्तिः॥',
    'oṁ asato mā sad gamaya / tamaso mā jyotir gamaya / mṛtyor mā amṛtaṁ gamaya / oṁ śāntiḥ śāntiḥ śāntiḥ',
    'From the unreal, lead me to the real; from darkness, lead me to light; from death, lead me to immortality.',
    'Bṛhadāraṇyaka Upaniṣad 1.3.28 (traditional, public domain Sanskrit text)',
    'Traditionally recited as an invocation for clarity, truth and inner light.',
    'Morning reflection, before study, meditation practice',
    'published'
),
(
    'Shanti Mantra — Peace Invocation', 'om-shanti-mantra',
    'ॐ शान्तिः शान्तिः शान्तिः॥',
    'oṁ śāntiḥ śāntiḥ śāntiḥ',
    'Peace in body, peace in mind and speech, peace in the surrounding world.',
    'Traditional Shanti Path (public domain Sanskrit text)',
    'Traditionally recited to close a practice session or reading, invoking peace.',
    'End of study, meditation, or recitation',
    'published'
);

-- ------------------------------------------------------------- teachings

INSERT INTO teachings (title, slug, life_problem, relevant_teaching, what_it_says, what_it_does_not_mean, how_to_apply, reflection_question, practice, status)
VALUES
(
    'I Am Afraid of Failing', 'fear-of-failing-teaching',
    'I am afraid of failing an exam or an important attempt, and the fear is paralyzing me.',
    'Krishna''s counsel to Arjuna on action without attachment to outcome (Bhagavad Gita 2.47-2.48) speaks directly to performance anxiety.',
    'You have the right to sincere effort, not to a guaranteed result. Fixating on the outcome undermines the very effort that could produce it.',
    'This does not mean you should stop caring about the result, or stop preparing seriously. Detachment here means releasing anxious grasping, not carelessness.',
    '1. Focus on preparation, not prediction.\n2. Reduce checking or comparing that feeds anxiety.\n3. Build a consistent, realistic study routine.\n4. Review mistakes without self-condemnation.\n5. Accept that outcomes involve factors beyond your control.',
    'What is fully within my control today, regarding this fear?',
    'Spend 30 focused minutes on preparation today without checking your phone.',
    'published'
),
(
    'I Get Angry Quickly', 'anger-teaching',
    'Small triggers make me angry fast, and I often regret what I say in the moment.',
    'The Gita traces anger to a specific chain: dwelling on something leads to attachment, then desire, then anger (2.62-2.63), which then clouds judgment.',
    'Anger is not random. It has a traceable root in what the mind keeps dwelling on. Interrupting the chain early prevents the explosion later.',
    'This does not mean suppressing anger or pretending it is not there. It means noticing the earlier attachment or desire before it escalates.',
    '1. Notice what you were dwelling on before the anger arose.\n2. Pause before reacting, even ten seconds helps.\n3. Name the underlying desire or attachment.\n4. Respond once the initial flare has passed.\n5. Reflect afterward on the trigger chain.',
    'What was I attached to, just before I got angry?',
    'Pause for 10 seconds before responding to a difficult message today.',
    'published'
),
(
    'I Am Grieving a Loss', 'grief-teaching',
    'I lost someone or something important, and the grief feels overwhelming.',
    'Krishna begins his teaching to a grieving Arjuna by pointing to the eternal, undying nature of the Self (Bhagavad Gita 2.20).',
    'The body changes and ends, but the deeper Self described here is said to be beyond birth and death — offered as a perspective, not a denial of loss.',
    'This does not mean grief is wrong, or that a loss should not be mourned. It is a perspective to sit alongside grief, not a replacement for mourning.',
    '1. Allow the grief its place; do not rush past it.\n2. Return gently to this perspective when ready, not to bypass mourning.\n3. Talk to someone you trust.\n4. Keep a small daily routine to hold steady.\n5. Revisit favorite memories without forcing conclusions.',
    'What has this loss taught me about what truly does not change?',
    'Write down one memory you are grateful for today.',
    'published'
);

-- ------------------------------------------------------------- relations

INSERT INTO verse_topics (verse_id, topic_id)
SELECT v.id, t.id FROM verses v JOIN chapters c ON c.id = v.chapter_id, topics t
WHERE c.gita_id = (SELECT id FROM gitas WHERE slug='bhagavad-gita') AND c.chapter_number = 2 AND (
 (v.verse_number=47 AND t.slug IN ('karma','detachment')) OR
 (v.verse_number=48 AND t.slug='detachment') OR
 (v.verse_number=62 AND t.slug='anger') OR
 (v.verse_number=63 AND t.slug='anger') OR
 (v.verse_number=20 AND t.slug IN ('self-atman','grief')) OR
 (v.verse_number=14 AND t.slug='detachment')
);

INSERT INTO verse_topics (verse_id, topic_id)
SELECT v.id, t.id FROM verses v JOIN chapters c ON c.id=v.chapter_id, topics t
WHERE c.gita_id=(SELECT id FROM gitas WHERE slug='bhagavad-gita') AND c.chapter_number=6 AND v.verse_number=5 AND t.slug IN ('discipline','self-atman');

INSERT INTO verse_topics (verse_id, topic_id)
SELECT v.id, (SELECT id FROM topics WHERE slug='self-atman') FROM verses v
WHERE v.gita_id = (SELECT id FROM gitas WHERE slug='ashtavakra-gita')
ON DUPLICATE KEY UPDATE verse_id = verse_id;

INSERT INTO verse_topics (verse_id, topic_id)
SELECT v.id, (SELECT id FROM topics WHERE slug='self-atman') FROM verses v
WHERE v.gita_id = (SELECT id FROM gitas WHERE slug='avadhuta-gita')
ON DUPLICATE KEY UPDATE verse_id = verse_id;

INSERT INTO verse_situations (verse_id, situation_id)
SELECT v.id, s.id FROM verses v JOIN chapters c ON c.id = v.chapter_id, situations s
WHERE c.gita_id = (SELECT id FROM gitas WHERE slug='bhagavad-gita') AND (
  (c.chapter_number=2 AND v.verse_number IN (47,48) AND s.slug='fear-of-failure') OR
  (c.chapter_number=2 AND v.verse_number IN (62,63) AND s.slug='anger') OR
  (c.chapter_number=2 AND v.verse_number=20 AND s.slug='grief-loss') OR
  (c.chapter_number=2 AND v.verse_number=14 AND s.slug='stress') OR
  (c.chapter_number=6 AND v.verse_number=5 AND s.slug='lack-of-discipline')
);

INSERT INTO teaching_topics (teaching_id, topic_id)
SELECT te.id, t.id FROM teachings te, topics t WHERE
 (te.slug='fear-of-failing-teaching' AND t.slug IN ('karma','detachment')) OR
 (te.slug='anger-teaching' AND t.slug='anger') OR
 (te.slug='grief-teaching' AND t.slug IN ('self-atman','grief'));

INSERT INTO teaching_situations (teaching_id, situation_id)
SELECT te.id, s.id FROM teachings te, situations s WHERE
 (te.slug='fear-of-failing-teaching' AND s.slug='fear-of-failure') OR
 (te.slug='anger-teaching' AND s.slug='anger') OR
 (te.slug='grief-teaching' AND s.slug='grief-loss');

INSERT INTO verse_teachings (verse_id, teaching_id)
SELECT v.id, te.id FROM verses v JOIN chapters c ON c.id=v.chapter_id, teachings te
WHERE c.gita_id=(SELECT id FROM gitas WHERE slug='bhagavad-gita') AND c.chapter_number=2 AND (
  (v.verse_number IN (47,48) AND te.slug='fear-of-failing-teaching') OR
  (v.verse_number IN (62,63) AND te.slug='anger-teaching') OR
  (v.verse_number=20 AND te.slug='grief-teaching')
);

INSERT INTO mantra_situations (mantra_id, situation_id)
SELECT m.id, s.id FROM mantras m, situations s WHERE
 (m.slug='om-asato-ma-sadgamaya' AND s.slug IN ('stress','fear-of-failure')) OR
 (m.slug='om-shanti-mantra' AND s.slug IN ('stress','anger','grief-loss'));

INSERT INTO mantra_teachings (mantra_id, teaching_id)
SELECT m.id, te.id FROM mantras m, teachings te WHERE
 (m.slug='om-asato-ma-sadgamaya' AND te.slug='fear-of-failing-teaching') OR
 (m.slug='om-shanti-mantra' AND te.slug IN ('anger-teaching','grief-teaching'));

-- -------------------------------------------------------------- wisdom

INSERT INTO daily_wisdom (wisdom_date, verse_id, title, short_message, practical_action, reflection_question, mantra_id, featured, status)
SELECT CURDATE(),
       (SELECT v.id FROM verses v JOIN chapters c ON c.id=v.chapter_id WHERE c.gita_id=(SELECT id FROM gitas WHERE slug='bhagavad-gita') AND c.chapter_number=2 AND v.verse_number=47),
       'Focus on Effort, Not Outcome',
       'A reminder from the Bhagavad Gita: your responsibility is sincere effort. The results are not yours to grasp.',
       'Complete your most important task today without checking how it will turn out.',
       'Where am I gripping too tightly to a result today?',
       (SELECT id FROM mantras WHERE slug='om-asato-ma-sadgamaya'),
       1, 'published';

-- -------------------------------------------------------------- settings

INSERT INTO settings (setting_key, setting_value, setting_group) VALUES
('site_name', 'Gita in Daily Life', 'general'),
('site_tagline', 'Ancient wisdom for modern life.', 'general'),
('default_meta_description', 'Discover teachings from multiple Gita traditions and learn how to apply them in everyday situations.', 'seo'),
('contact_email', 'callcenter.ovijat@gmail.com', 'general'),
('pagination_size', '12', 'content'),
('default_language', 'en', 'content');
