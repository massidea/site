-- -- Reset Tables
TRUNCATE TABLE `users_usr`;
TRUNCATE TABLE `contents_cnt`;
TRUNCATE TABLE `usr_groups_grp`;
TRUNCATE TABLE `campaigns_cmp`;
TRUNCATE TABLE `cmp_has_cnt`;
TRUNCATE TABLE `cnt_has_grp`;
TRUNCATE TABLE `comments_cmt`;
TRUNCATE TABLE `grp_has_admin_usr`;
TRUNCATE TABLE `usr_has_grp`;
TRUNCATE TABLE `usr_profiles_usp`;

--
-- Table `users_usr`
-- Username: Admin  Password: admin
-- Username: User   Password: user
--
INSERT INTO `users_usr` (`id_lng_usr`, `login_name_usr`, `password_usr`, `password_salt_usr`, `email_usr`, `gravatar_usr`, `first_name_usr`, `surname_usr`, `gender_usr`, `last_login_usr`, `created_usr`, `modified_usr`) VALUES
(12, 'Admin', 'e69d45f250a349b83025bbcec46ac873', 'Aic5I46H9FV3x7lyQ0NAnlICli0AZjSgu97p0nyCb2TtthbUb8p6wB6IeCuCw0EOVxVxbuiUvCK6heJNed9PyzmmQ2hbBesSz9lR4OKA8j3hOztdZWL5sALd3GMNJfOu', 'admin@massidea.com', 0, NULL, NULL, NULL, '2012-11-13 16:42:48', '2012-11-13 16:42:48', '2012-11-13 16:42:48'),
(12, 'User', '062b2817fe26ec1cf9de584425690750', 'SqHj88xrkqN4LCRMYuRASBGTdlDkCrAxkBRZMxuZcttOeEE22fIxqRt7iFP0hKeZPNyIwdE9tolaL8PatJVcdYve9by8ZfV4SStuuCwbWc1eOux17p69mYJ2yqu9oskR', 'user@massidea.com', 0, NULL, NULL, NULL, '2012-11-13 16:44:49', '2012-11-13 16:44:49', '2012-11-13 16:44:49');


--
-- Table `contents_cnt`
-- Adds 1 Challenge, 1 Vision, 1 Idea
--
INSERT INTO `contents_cnt` (`id_cty_cnt`, `title_cnt`, `lead_cnt`, `language_cnt`, `body_cnt`, `research_question_cnt`, `opportunity_cnt`, `threat_cnt`, `solution_cnt`, `references_cnt`, `views_cnt`, `published_cnt`, `created_cnt`, `modified_cnt`) VALUES
(1, 'Solution of Tap water problems in St. Petersburg caused by Giardia.', 'If we want to fix this problem we need help from government because it can use its power to improve the situation. Indeed, companies don’t have a real interest to exhale really clean safe and pure water, otherwise to get as more as possible profit, they just look for the cheapest way at the expense of quality.', '23',
'First, government should be interested in controlling all companies, factories producing any kind of waste. The government should set up some rules, laws and limits of amount of pollution that factories and companies can exhale into the river. Our idea is to convince government for instance we can use this kind of independent organization like Green peace. Through it, we can get more power and we need to find proof and make report to be credible. Then if we get attention to the government they will established some new directives to water filtration companies. This insight is really important because it talks about a main problem in the system of health improvement. First we did wrong thing because we didn’t really know what will be troubles about the way how we produce. In this case of tap water, companies which are in charge of water purification are using to filter with a certain way. But now, we know it’s not enough cleaning because of diseases caused by parasit called Giardia. Now because of there are too many economics issues the only way to change minds is to show that we can’t stay with this old system because people get sick and some of them died. Through independent companies like green peace we try to show that because it’s the last chance. We can easily explain who the main targets in this topic are. First we have the population of St Peterburg who already got sick with tap water but also potential patients. To solve this problem we use an independent organisation who will show how this phenomenon works and which are the consequences. But also make some advice to the government how to manage it. Moreover there is the government: if he agrees, with this idea, to get better tap water, or just because of media pressure, or population pressure, and then he will make pressure to water filtration companies with new limitation, safety standards. So the last target group in this topic should be water purification companies which will just have to follow new rules. We also see an opportunity for entrepreneurs, who would be able to produce small household cleaning purification stations, which would be installed for example under the sink. These stations would provide clean water for household members and could of course be also used for example in schools, restaurants, offices, etc. Your idea/solution in one sentence: Involving a government in solving problems with clean tap water and also there is a oportunity for producers of small purification systems', NULL, NULL, NULL, NULL, NULL, 0, 1, '2012-11-13 15:31:41', '2012-11-13 15:31:45'),
(2, 'Nanotechnology', 'The biggest problem for womam is to get a work with out discrimination of her rights. The solution for this problem may be found by examining human evolution and utilizing modern technologies.', '23',
'Making use of knowledge about the most basic aspects of human nature in combination with the most sophisticated technologies it is possible to work out a solution that will take advantage of men’s behaviour and even out the playing field for (or perhaps even give an edge to) women. At the first glance an inconspicuous item – a necklace around a woman’s neck – is what actually grants that effect. In this necklace are embedded sensors, emitters, a small data storage, a processing unit and a power source. When combined, these nanotechnological devices will transfer images (or, more accurately, knowledge) of what the woman before the subject is. However, before proceeding to purpose, function and effect, it is necessary to start with a reason, and the reason why it is a necklace and not a different item is hidden in the very basics of human nature. Evolution has equipped men with certain behavioural patterns that are all but instinctual. The purpose of life is to reproduce and thrive, at that, humans seek to find the most suitable mate to project their genes further. Men tend to look at women’s chests and waists, as these characteristics are signaling higher estrogen level which in turn means greater fertility. Therefore, a necklace falls into a topologically convenient place and will draw attention to it, as it is within the boundaries of the so called “hourglass figure” held in high esteem by men. Speaking of esteem, though, it is necessary to add that a glance at a component of the hourglass figure is not always guided by instinct. Aesthetics also play an important role, however, in this respect, the outcome mostly depends on the woman herself. Once the man’s gaze is fixed on the necklace, the nanotech sensors detect the direct gaze of the subject’s eyes and signal the microprocessor and data storage unit to compute the information for transfer into the subject’s mind, this information is then projected onto the subject’s retinas through the emitters creating a series of images so fleeting that the subject’s sight is not replaced by them, however, the memory of those images persists. This process, however fast, still requires some time to complete, therefore, the necklace is made aesthetically intricate in order to maintain the subject’s gaze at it long enough as he quickly examines the details. The process is complete after roughly 300 ms (or 0.3 seconds) which is the time required to blink an eye. The information stored inside the data storage is basically quasi-firsthand knowledge of the woman’s capabilities as an employee of a company. The interviewer sees exactly what the woman is generally able to do, before she gets the chance to do it. This will liberate the woman of the necessity to prove herself to her superiors over and over, before (or, sadly, if) she actually is considered to be on the level with men.', NULL, NULL, NULL, NULL, NULL, 0, 1, '2012-11-13 15:49:48', '2012-11-13 15:49:51'),
(2, 'Noise pollution generated by road freight transport in settlement area', 'As a result of climatic changes are expected to the development of road freight transport, which increased noise adversely affecting the health of residents in long-term and short-term consequences.', '23',
'4.1 What is the insight? The climate change is bringing some changes of transport links throughout the World. Scientific studies confirm that due to the temperature rise and melting glaciers weakens the strength of the Gulf Stream. There is a slight but sustained cooling of the climate on the continent. In consequence, in the future will freeze certain ports in Europe, which is now still available. This issue touches and follow road freight transport, which will have to take greater demands on the transportation of goods. The crucial question will be how to deal with the road infrastructure, which will be faced with new challenges. Consequently, it will be important how we manage to eliminate the negative impacts such as increased noise that road freight transport entails. 4.2 Why is the Important and valuable insight? This view of the problem is the add-on parts, since most of the predictions is limited to the direct effects of a single bond. Thus, in this context, to reduce transport links due to low temperatures in northern Europe for sea and air freight. But it is necessary to analyze the deeper implications in a broader context. Thus, to focus on the negative effects associated with the operation of connecting road freight transport in urban areas, where road infrastructure is not ready increased burden of road freight to satisfy. 4.3 Who is the target group and Who should be interested? Increasing intensity of freight transport also brings excessive population exposure to noise. Carriers in an effort to find other transportation routes overwhelm road infrastructure to such a load is not being proposed - the second road classes and 3 classes. In its basic form are the most affected residents who live near the route of logistical centers (places the source or destination transport). The issue of noise pollution and road infrastructure and lack of preparedness to deal with virtually smaller government units (municipalities, etc ...) which have no satisfactory solution has sufficient funds. Must thus turn to the state, which already does not have this problem under control as a local government. His contribution is in the field of hygiene setting noise limits, which legislatively approved by the Government of the Czech Republic. 4.4 When is the topical insight? There is already a trend of increasing noise pollution and other negative impacts of road transport. These are short-term and long-term effects on human health (stress, nervous tension), manifested among other things increased morbidity. Although the conclusions of the White Paper of the European Union recommended freight facing head on rail transport, are more realistically, investments in road infrastructure. In the 10 years, according to data available will increase the volume of freight transport by 20%. This burden will have to take the highway infrastructure. 4.5 Where is the insight topical? At present, increasing the intensity of road freight traffic passing through the town. Specifically, in terms of Prague, excess noise pollution especially affected residents along the roads leading to the center. At present, the annual rate of growth of road freight transport crossing the border of Prague 5%. With the increasing as well as noise pollution for residents of Prague.', NULL, NULL, NULL, NULL, NULL, 0, 1, '2012-11-13 15:50:26', '2012-11-13 15:50:29');


--
-- Table `usr_groups_grp`
-- Creates two groups:
-- 'FH-Hagenberg Group' -> closed Group
-- 'Test Open Group'    -> open Group
--
INSERT INTO `usr_groups_grp` (`id_sth_grp`, `group_name_grp`, `description_grp`, `body_grp`, `has_blog_grp`, `image_grp`, `created_grp`, `modified_grp`, `id_type_grp`) VALUES
(0, 'FH-Hagenberg Group', 'Hagenberg Group', 'Development group from Fh-Hagenberg', 0, NULL, '2012-11-13 16:08:15', '2012-11-13 16:08:18', 2),
(0, 'Laurea A0105 - Autumn 2012', 'This group is used to familiarize BIT2012 students in the A0105 study-unit with the basic features of Massidea', 'The purpose of this campaign is to help students of A0105 study-unit in getting familiar with the basic functions of Massidea', 0, NULL, '2012-11-13 16:10:16', '2012-11-13 16:10:18', 1);


--
-- Table `campaigns_cmp`
-- Creates a test project
--
INSERT INTO `campaigns_cmp` (`id_grp_cmp`, `id_cty_cmp`, `name_cmp`, `ingress_cmp`, `description_cmp`, `image_cmp`, `start_time_cmp`, `end_time_cmp`, `created_cmp`, `modified_cmp`) VALUES
(1, 0, 'Nokia Bridge Program', 'In this campaign students and Nokia representative are discussing objectives, implementation and challenges of the Bridge Program.',
'Before starting the discussion please study the presentation material in your Optima workspace. Find these challenges and post your individual comments and a new idea of your team: 1.Objectives of Bridge Program What kind of targets should be given to the persons implementing the Bridge Program in Nokia local offices? 2.Success factors and measurement a) In your opinion, what is important for the success of Bridge Program? b) How would you measure the success of Bridge Program? 3.Bridge Program communication a) In your opinion, what is important when communicating the Bridge Program to the employees? b) In your opinion, what is important when communicating the Bridge Program to stakeholders outside the company? eg. customers 4. Implementation In your opinion, what might be the challenges of implementing the Bridge Program on global level? How could the impact of cultural factors be taken into account? 5. Working as a member of the Bridge Program How would you motivate the people who work in the Bridge Program team? 6. Attitudes towards Bridge Program a) In your opinion, what would the people who are about to lose their jobs think about the Bridge Program? b) In your opinion, what would the employees who continue with the company think about the Bridge Program? 7. Is this enough? What could Nokia do differently when downsizing? Should Nokia add something to the Bridge Program? 8. Why does Nokia offer the Bridge Program? What do you think?', NULL, '2012-11-13', '2015-11-13', '2012-11-13 16:25:16', '2012-11-13 16:25:23');


--
-- Table `cmp_has_cnt`
-- adds content with id '2' to project with id '1'
--
INSERT INTO `cmp_has_cnt` (`id_cmp`, `id_cnt`) VALUES
(1, 2);


--
-- Table `cnt_has_grp`
-- adds content (id 2) to group (id 1)
--
INSERT INTO `cnt_has_grp` (`id_cnt`, `id_grp`, `owner_cnt_grp`) VALUES
(2, 1, 1);


--
-- Table 'comments_cmt'
-- adds a comment to content with id (2)
--
INSERT INTO `massidea`.`comments_cmt` (`id_target_cmt`, `id_usr_cmt`, `id_parent_cmt`, `title_cmt`, `body_cmt`, `created_cmt`, `modified_cmt`, `type_cmt`) VALUES
('2', '1', '0', 'Bridge Program implementation', 'In your opinion, what might be the challenges of implementing the Bridge Program on global level? How could the impact of cultural factors be taken into account?', '2012-11-13 16:36:29', '2012-11-13 16:36:32', '2');


--
-- Table `grp_has_admin_usr`
-- user 'Admin' (id 1) is set as group admin for groups 'FH-Hagenberg Group' and 'Test Open Group'
--
INSERT INTO `grp_has_admin_usr` (`id_usr`, `id_grp`) VALUES
(1, 1),
(1, 2);


--
-- Table `usr_has_grp`
-- adds user 'User' to group 'Test Open Group'
--
INSERT INTO `usr_has_grp` (`id_usr`, `id_grp`) VALUES
(2, 1);


--
-- Table `usr_profiles_usp`
-- adds profile data for the users 'Admin' and 'User'
--
INSERT INTO `usr_profiles_usp` (`id_usr_usp`, `profile_key_usp`, `profile_value_usp`, `public_usp`, `created_usp`, `modified_usp`) VALUES
(1, 'employment', 'student', 0, NULL, '2012-11-13 16:42:48'),
(1, 'city', 'Hagenberg', 1, NULL, '2012-11-13 16:42:48'),
(2, 'employment', 'student', 0, NULL, '2012-11-13 16:44:49'),
(2, 'city', 'Hagenberg', 1, NULL, '2012-11-13 16:44:49');
