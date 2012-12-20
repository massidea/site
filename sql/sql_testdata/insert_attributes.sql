#########################################
### INSERTING ALL DEFINED ATTRIBUTES ###
#########################################

# Reset Table
TRUNCATE TABLE `attributes_atr`;

# Inserts ##############################
# Language Teachers #
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Language teachers');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('English');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('German');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('French');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Spanish');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Reading');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Writing');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('English in Use');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Listening');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Speaking');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Miscellaneous');

# Cross-cultural teachers #
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Cross-cultural teachers');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Interest in Europe');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Interest in Asia');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Interest in Africa');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Interest in America');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Interest in Australia');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Interest in Mutual Feedback');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Common Tasks');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Cross-Cultural Comparison');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Joint Papers');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Conflict Management');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('Online Communication');

# University #
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('University');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('University of Applied Sciences');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('School of Information & Communication Technology');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('School of Natural Sciences');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('School of Social Sciences');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('School of Health Sciences');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('School of Environmental Sciences');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('School of Humanities');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('School of Engineering');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('School of Law and Finance');
INSERT INTO `attributes_atr` (`name_atr`) VALUES ('School of Management ');


#########################################
### INSERTING ALL DEFINED Categories ###
#########################################

# Reset Table
TRUNCATE TABLE `categories_ctg`;

# Inserts ##############################
INSERT INTO `categories_ctg` (`title_ctg`) VALUES ('Information & Communication Technology');
INSERT INTO `categories_ctg` (`title_ctg`) VALUES ('Natural Sciences');
INSERT INTO `categories_ctg` (`title_ctg`) VALUES ('Social Sciences');
INSERT INTO `categories_ctg` (`title_ctg`) VALUES ('Health Sciences');
INSERT INTO `categories_ctg` (`title_ctg`) VALUES ('Environmental Sciences');
INSERT INTO `categories_ctg` (`title_ctg`) VALUES ('Humanities');
INSERT INTO `categories_ctg` (`title_ctg`) VALUES ('Engineering');
INSERT INTO `categories_ctg` (`title_ctg`) VALUES ('Law and Finance');
INSERT INTO `categories_ctg` (`title_ctg`) VALUES ('Management ');


#########################################
### INSERTING ALL DEFINED Jobs ###
#########################################

# Reset Table
TRUNCATE TABLE `categories_ctg`;

# Inserts ##############################
INSERT INTO `jobs_job` (`description_job`) VALUES ('Private Sector');
INSERT INTO `jobs_job` (`description_job`) VALUES ('Public Sector');
INSERT INTO `jobs_job` (`description_job`) VALUES ('Education Sector');
INSERT INTO `jobs_job` (`description_job`) VALUES ('Language Teacher');
INSERT INTO `jobs_job` (`description_job`) VALUES ('Cross-cultural Teacher');
INSERT INTO `jobs_job` (`description_job`) VALUES ('Student');
INSERT INTO `jobs_job` (`description_job`) VALUES ('Pentioner');
INSERT INTO `jobs_job` (`description_job`) VALUES ('Other');