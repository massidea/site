USE oibs;
INSERT INTO comment_types_ctp (type_ctp, type_name_ctp) VALUES (1, 'account'), (2, 'content'), (3, 'campaign');
RENAME TABLE comment_types_ctp TO page_types_ptp;
ALTER TABLE page_types_ptp CHANGE id_ctp id_ptp INT AUTO_INCREMENT UNIQUE NOT NULL;
ALTER TABLE page_types_ptp CHANGE type_ctp type_ptp INT UNIQUE NOT NULL;
ALTER TABLE page_types_ptp CHANGE type_name_ctp type_name_ptp VARCHAR(50) UNIQUE NOT NULL;
