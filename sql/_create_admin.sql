/* 
 * For old user, which has not permissions-field in user profile table, use this SQL insertion
 * Remember to replace <USERID> with correct user id
*/
INSERT INTO usr_profiles_usp (id_usr_usp, profile_key_usp, profile_value_usp, public_usp, created_usp, modified_usp) VALUES (<USERID>, 'permissions', '["user","admin"]', 1, NOW(), NOW());

/* 
 * For new user, which has permissions-field in user profile table, use this SQL update
 * Remember to replace <USERID> with correct user id
*/

UPDATE usr_profiles_usp SET profile_value_usp = '["user","admin"]' WHERE id_usr_usp = <USERID> AND profile_key_usp = 'permissions';