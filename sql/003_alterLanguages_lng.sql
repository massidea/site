ALTER TABLE languages_lng
ADD COLUMN active_lng BOOLEAN DEFAULT false;

UPDATE languages_lng
SET active_lng=true
WHERE (name_lng='English') OR (name_lng='Finnish');