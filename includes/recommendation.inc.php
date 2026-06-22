<?php

function skillgap_split_skills($value) {
    $value = strtolower((string) $value);
    $parts = preg_split('/[,;\n]+/', $value);
    $skills = [];

    foreach ($parts as $part) {
        $skill = trim($part);
        if ($skill !== '') {
            $skills[$skill] = true;
        }
    }

    return array_keys($skills);
}

function skillgap_match_role($learnerSkills, $requiredSkills) {
    $learner = array_flip(skillgap_split_skills($learnerSkills));
    $required = skillgap_split_skills($requiredSkills);
    $matched = [];
    $missing = [];

    foreach ($required as $skill) {
        if (isset($learner[$skill])) {
            $matched[] = $skill;
        } else {
            $missing[] = $skill;
        }
    }

    $score = count($required) === 0 ? 0 : (int) round((count($matched) / count($required)) * 100);

    return [
        'score' => $score,
        'matched' => $matched,
        'missing' => $missing,
    ];
}

function skillgap_fetch_roles($conn) {
    $roles = [];
    $result = mysqli_query($conn, "SELECT * FROM job_roles ORDER BY demand_level DESC, title ASC");

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $roles[] = $row;
        }
    }

    return $roles;
}

function skillgap_fetch_jobs($conn, $roleId = null) {
    $jobs = [];

    if ($roleId) {
        $stmt = $conn->prepare("SELECT * FROM job_opportunities WHERE role_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $roleId);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = mysqli_query($conn, "SELECT j.*, r.title AS role_title FROM job_opportunities j LEFT JOIN job_roles r ON j.role_id = r.role_id ORDER BY j.created_at DESC");
    }

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $jobs[] = $row;
        }
    }

    return $jobs;
}

function skillgap_best_matches($conn, $learner, $limit = 3) {
    $matches = [];

    foreach (skillgap_fetch_roles($conn) as $role) {
        $match = skillgap_match_role($learner['current_skills'] ?? '', $role['required_skills'] ?? '');
        $role['match_score'] = $match['score'];
        $role['matched_skills'] = $match['matched'];
        $role['missing_skills'] = $match['missing'];
        $matches[] = $role;
    }

    usort($matches, function ($a, $b) {
        return $b['match_score'] <=> $a['match_score'];
    });

    return array_slice($matches, 0, $limit);
}
