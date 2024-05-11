CREATE TABLE IF NOT EXISTS class_groups(
    id SERIAL PRIMARY KEY,
    group_name TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS universities(
    id SERIAL PRIMARY KEY,
    university_name TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS teachers(
    id SERIAL PRIMARY KEY,
    full_name TEXT NOT NULL,
    email TEXT NOT NULL,
    password text null
);

CREATE TABLE IF NOT EXISTS students(
    id SERIAL PRIMARY KEY,
    full_name TEXT NOT NULL,
    email TEXT NOT NULL,
    group_id int NOT NULL,
    group_head bool NOT NULL,
    password text null,
    FOREIGN KEY (group_id) REFERENCES class_groups(id)
);

CREATE TABLE IF NOT EXISTS classes(
    id SERIAL PRIMARY KEY,
    class_start timestamp NOT NULL,
    class_end timestamp NOT NULL,
    place TEXT NOT NULL,
    university_id int NOT NULL,
    group_id int NOT NULL,
    teacher_id int NOT NULL,
    subject TEXT NOT NULL,
    uid uuid NOT NULL,
    FOREIGN KEY (university_id) REFERENCES universities(id),
    FOREIGN KEY (group_id) REFERENCES class_groups(id),
    FOREIGN KEY (teacher_id) REFERENCES teachers(id)
);

CREATE TABLE IF NOT EXISTS class_attendance(
    id SERIAL PRIMARY KEY,
    class_id int NOT NULL,
    student_id int NOT NULL,
    status NUMERIC(2,1) NULL,
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (student_id) REFERENCES students(id)
);

CREATE TABLE IF NOT EXISTS class_attendance_group_head(
   id SERIAL PRIMARY KEY,
   student_id int NOT NULL,
   class_id int NOT NULL,
   FOREIGN KEY (student_id) REFERENCES students(id),
   FOREIGN KEY (class_id) REFERENCES classes(id)
);

CREATE TABLE IF NOT EXISTS class_attendance_students(
    id SERIAL PRIMARY KEY,
    group_id int NOT NULL,
    class_id int NOT NULL,
    count int NOT NULL,
    time_end timestamp NOT NULL,
    hash TEXT NOT NULL,
    FOREIGN KEY (group_id) REFERENCES class_groups(id),
    FOREIGN KEY (class_id) REFERENCES classes(id)
);
