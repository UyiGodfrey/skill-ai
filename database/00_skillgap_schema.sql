SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS recommendations;
DROP TABLE IF EXISTS model_predictions;
DROP TABLE IF EXISTS skill_assessments;
DROP TABLE IF EXISTS job_opportunities;
DROP TABLE IF EXISTS application;
DROP TABLE IF EXISTS message;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS rating;
DROP TABLE IF EXISTS hostel_manager;
DROP TABLE IF EXISTS room;
DROP TABLE IF EXISTS hostel;
DROP TABLE IF EXISTS student;
DROP TABLE IF EXISTS job_roles;
DROP TABLE IF EXISTS platform_admins;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE job_roles (
  role_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(160) NOT NULL,
  category VARCHAR(120) NOT NULL,
  required_skills TEXT NOT NULL,
  nice_to_have_skills TEXT NULL,
  avg_salary VARCHAR(80) NULL,
  demand_level ENUM('Low', 'Medium', 'High') NOT NULL DEFAULT 'Medium',
  learning_path TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE student (
  Student_id VARCHAR(255) NOT NULL PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  Fname VARCHAR(255) NOT NULL,
  Lname VARCHAR(255) NOT NULL,
  Mob_no VARCHAR(255) NULL,
  Dept VARCHAR(255) NULL,
  Year_of_study VARCHAR(255) NULL,
  Pwd LONGTEXT NOT NULL,
  status TINYINT(1) NOT NULL DEFAULT 1,
  target_role_id INT NULL,
  current_skills TEXT NULL,
  desired_roles TEXT NULL,
  experience_level VARCHAR(80) NOT NULL DEFAULT 'Entry',
  career_goal TEXT NULL,
  portfolio_url VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT student_target_role_fk FOREIGN KEY (target_role_id) REFERENCES job_roles(role_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE skill_assessments (
  assessment_id INT AUTO_INCREMENT PRIMARY KEY,
  Student_id VARCHAR(255) NOT NULL,
  skill_name VARCHAR(120) NOT NULL,
  proficiency TINYINT UNSIGNED NOT NULL DEFAULT 1,
  evidence_url VARCHAR(255) NULL,
  assessed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT assessment_student_fk FOREIGN KEY (Student_id) REFERENCES student(Student_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE job_opportunities (
  job_id INT AUTO_INCREMENT PRIMARY KEY,
  role_id INT NOT NULL,
  company VARCHAR(160) NOT NULL,
  title VARCHAR(180) NOT NULL,
  location VARCHAR(160) NOT NULL,
  employment_type VARCHAR(80) NOT NULL,
  salary_range VARCHAR(120) NULL,
  required_skills TEXT NOT NULL,
  description TEXT NULL,
  source_url VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT opportunity_role_fk FOREIGN KEY (role_id) REFERENCES job_roles(role_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE recommendations (
  recommendation_id INT AUTO_INCREMENT PRIMARY KEY,
  Student_id VARCHAR(255) NOT NULL,
  role_id INT NOT NULL,
  match_score DECIMAL(5,2) NOT NULL,
  missing_skills TEXT NULL,
  recommended_jobs JSON NULL,
  model_version VARCHAR(80) NOT NULL DEFAULT 'rule-v1',
  generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT recommendation_student_fk FOREIGN KEY (Student_id) REFERENCES student(Student_id) ON DELETE CASCADE,
  CONSTRAINT recommendation_role_fk FOREIGN KEY (role_id) REFERENCES job_roles(role_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE model_predictions (
  prediction_id INT AUTO_INCREMENT PRIMARY KEY,
  Student_id VARCHAR(255) NOT NULL,
  role_id INT NOT NULL,
  model_name VARCHAR(120) NOT NULL,
  match_score DECIMAL(5,2) NOT NULL,
  payload JSON NULL,
  imported_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT prediction_student_fk FOREIGN KEY (Student_id) REFERENCES student(Student_id) ON DELETE CASCADE,
  CONSTRAINT prediction_role_fk FOREIGN KEY (role_id) REFERENCES job_roles(role_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE platform_admins (
  admin_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(120) NOT NULL UNIQUE,
  full_name VARCHAR(180) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO job_roles (title, category, required_skills, nice_to_have_skills, avg_salary, demand_level, learning_path) VALUES
('Data Analyst', 'Analytics', 'excel, sql, python, data visualization, statistics', 'power bi, tableau, storytelling', '$55k-$85k', 'High', 'Strengthen SQL joins, build Python analysis notebooks, then publish two dashboard case studies.'),
('Backend Developer', 'Software Engineering', 'php, mysql, api design, git, security', 'docker, testing, laravel', '$65k-$105k', 'High', 'Practice API CRUD flows, authentication, SQL optimization, and automated tests.'),
('AI/ML Engineer', 'Artificial Intelligence', 'python, machine learning, statistics, sql, model evaluation', 'pandas, scikit-learn, deep learning, mlops', '$85k-$140k', 'High', 'Build supervised learning projects, track metrics, package models, and document deployment assumptions.'),
('Product Designer', 'Design', 'user research, wireframing, prototyping, usability testing, visual design', 'figma, design systems, accessibility', '$60k-$110k', 'Medium', 'Create research notes, low-fidelity flows, clickable prototypes, and usability evidence.');

INSERT INTO job_opportunities (role_id, company, title, location, employment_type, salary_range, required_skills, description, source_url) VALUES
(1, 'Northstar Fintech', 'Junior Data Analyst', 'Remote', 'Full-time', '$50k-$70k', 'excel, sql, data visualization', 'Analyze operational metrics and build weekly reporting dashboards.', '#'),
(1, 'Campus Labs', 'BI Reporting Intern', 'Hybrid', 'Internship', '$18-$25/hr', 'excel, sql, power bi', 'Support dashboard refreshes and data quality checks.', '#'),
(2, 'StackWorks', 'PHP Backend Developer', 'Lagos / Remote', 'Full-time', '$45k-$80k', 'php, mysql, api design, git', 'Maintain web application services and internal APIs.', '#'),
(3, 'Insight Forge', 'Machine Learning Trainee', 'Remote', 'Contract', '$35-$55/hr', 'python, machine learning, model evaluation', 'Prototype recommendation models and evaluate prediction quality.', '#'),
(4, 'Studio Grid', 'Associate Product Designer', 'Hybrid', 'Full-time', '$55k-$85k', 'user research, wireframing, prototyping', 'Design user flows for career and education products.', '#');

INSERT INTO platform_admins (username, full_name, password_hash) VALUES
('admin', 'SkillBridge Admin', SHA2('admin123', 256));
