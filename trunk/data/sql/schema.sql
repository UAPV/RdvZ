CREATE TABLE meeting (id BIGINT AUTO_INCREMENT, hash VARCHAR(8) NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, uid BIGINT, closed TINYINT(1) DEFAULT '0', date_del DATETIME, date_end DATETIME, notif TINYINT(1) DEFAULT '0', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX uid_idx (uid), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE meeting_date (id BIGINT AUTO_INCREMENT, mid BIGINT NOT NULL, date DATETIME NOT NULL, comment VARCHAR(255), INDEX mid_idx (mid), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE meeting_poll (id BIGINT AUTO_INCREMENT, date_id BIGINT NOT NULL, poll BIGINT NOT NULL, uid BIGINT, comment VARCHAR(255), participant_name VARCHAR(255), created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX date_id_idx (date_id), INDEX uid_idx (uid), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE user (id BIGINT AUTO_INCREMENT, ldap_id VARCHAR(50) UNIQUE, login VARCHAR(50) UNIQUE, pass VARCHAR(40), name VARCHAR(255), surname VARCHAR(255), mail VARCHAR(255), PRIMARY KEY(id)) ENGINE = INNODB;
ALTER TABLE meeting ADD CONSTRAINT meeting_uid_user_id FOREIGN KEY (uid) REFERENCES user(id) ON DELETE CASCADE;
ALTER TABLE meeting_date ADD CONSTRAINT meeting_date_mid_meeting_id FOREIGN KEY (mid) REFERENCES meeting(id) ON DELETE CASCADE;
ALTER TABLE meeting_poll ADD CONSTRAINT meeting_poll_uid_user_id FOREIGN KEY (uid) REFERENCES user(id) ON DELETE CASCADE;
ALTER TABLE meeting_poll ADD CONSTRAINT meeting_poll_date_id_meeting_date_id FOREIGN KEY (date_id) REFERENCES meeting_date(id) ON DELETE CASCADE;
