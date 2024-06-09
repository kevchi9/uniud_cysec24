BEGIN;

CREATE TABLE users (
username varchar(20) NOT NULL PRIMARY KEY,
pswd varchar(255) NOT NULL
);


CREATE TABLE pkey (
username varchar(20) NOT NULL,
pkey TEXT PRIMARY KEY,
CONSTRAINT foreign_pkey_key FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
);


CREATE TABLE encrypted_files (
id SERIAL PRIMARY KEY,
encrypted_data TEXT NOT NULL,
encrypted_key TEXT NOT NULL,
uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
file_type TEXT NOT NULL,
file_name TEXT NOT NULL
);


CREATE TABLE published_encrypted_files (
id SERIAL PRIMARY KEY,
encrypted_data TEXT NOT NULL,
publisher varchar(20) NOT NULL,
uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
file_name TEXT NOT NULL,
CONSTRAINT foreign_publisher_key1 FOREIGN KEY (publisher) REFERENCES users(username) ON DELETE CASCADE
);

CREATE TABLE own_file (
username varchar(20) NOT NULL,
file INTEGER NOT NULL, 
CONSTRAINT own_file_primary_key PRIMARY KEY (username, file),
CONSTRAINT foreign_own_file_key1 FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE,
CONSTRAINT foreign_own_file_key2 FOREIGN KEY (file) REFERENCES encrypted_files(id) ON DELETE CASCADE
);


CREATE or REPLACE FUNCTION verify(user_name varchar(20), passwd varchar(255))
RETURNS int 
as
$$
DECLARE
verified int;
BEGIN
select 1 into verified from users where username=user_name and pswd=crypt(passwd, pswd);
return coalesce(verified, 0);
END;
$$
language plpgsql;



CREATE or REPLACE FUNCTION does_exist(user_name varchar(20))
RETURNS int
language plpgsql
as 
$$
DECLARE
exists int;
BEGIN
SELECT 1 into exists FROM users WHERE username=user_name;
return coalesce(exists, 0);
END;
$$;

COMMIT;
