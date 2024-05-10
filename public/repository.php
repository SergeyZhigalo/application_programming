<?php

function getAllUniversities(): array|false
{
    $stmt = pdo()->query("SELECT * FROM universities");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllGroups(): array|false
{
    $stmt = pdo()->query("SELECT * FROM class_groups");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTeachersClasses(string $id): array|false
{
    $stmt = pdo()->prepare("
        SELECT classes.*, class_groups.group_name, universities.university_name
        FROM classes
        JOIN class_groups ON classes.group_id = class_groups.id
        JOIN universities ON classes.university_id = universities.id
        WHERE teacher_id = :teacher_id
        ORDER BY class_start DESC;
    ");
    $stmt->execute(['teacher_id' => $id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getClassById(string $id): array|false
{
    $stmt = pdo()->prepare("
        SELECT classes.*, class_groups.group_name, universities.university_name
        FROM classes
        JOIN class_groups ON classes.group_id = class_groups.id
        JOIN universities ON classes.university_id = universities.id
        WHERE classes.id = :id;
    ");
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getStudentsByGroupId(string $id): array|false
{
    $stmt = pdo()->prepare("SELECT * FROM students WHERE group_id = :group_id ORDER BY full_name;");
    $stmt->execute(['group_id' => $id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getClassesByUniversityIdGroupIdTeacherIdSubject(string $universityId, string $groupId, string $teacherId, string $subject): array|false
{
    $stmt = pdo()->prepare("
        SELECT *
        FROM classes
        WHERE university_id = :university_id AND group_id = :group_id AND teacher_id = :teacher_id AND subject = :subject
        ORDER BY class_start;
    ");
    $stmt->execute([
        'university_id' => $universityId,
        'group_id' => $groupId,
        'teacher_id' => $teacherId,
        'subject' => $subject,
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getClassAttendanceByClassIds(array $ids): array|false
{
    $classIds = implode(',', $ids);
    $stmt = pdo()->prepare("SELECT * FROM class_attendance WHERE class_id IN ($classIds);");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllClassesByTeacherId(string $id): array|false
{
    $stmt = pdo()->prepare("SELECT * FROM classes WHERE teacher_id = :teacher_id;");
    $stmt->execute(['teacher_id' => $id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
