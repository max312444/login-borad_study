-- 데이터베이스 생성 (존재하지 않으면)
CREATE DATABASE IF NOT EXISTS gsc CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- gsc 데이터베이스 사용
USE gsc;

-- student 테이블 생성
CREATE TABLE IF NOT EXISTS student (
    no INT AUTO_INCREMENT PRIMARY KEY,           -- 순번 (자동 증가)
    std_id VARCHAR(20) NOT NULL UNIQUE,          -- 학번 (유일)
    id VARCHAR(20) NOT NULL UNIQUE,              -- 로그인 ID (유일)
    password VARCHAR(100) NOT NULL,              -- 비밀번호 (해싱 필요)
    name VARCHAR(50) NOT NULL,                   -- 이름
    age INT,                                      -- 나이
    birth DATE                                    -- 생년월일
);

-- users 테이블 생성
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- -- 데이터베이스 생성
-- CREATE DATABASE IF NOT EXISTS myapp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- -- 사용자 생성 (비밀번호는 'password123')
-- CREATE USER IF NOT EXISTS 'myuser'@'%' IDENTIFIED BY 'password123';

-- -- 권한 부여
-- GRANT ALL PRIVILEGES ON myapp.* TO 'myuser'@'%';
-- FLUSH PRIVILEGES;

-- -- 테이블 생성
-- USE myapp;



-- -- 초기 데이터 삽입
-- INSERT INTO users (username, email) VALUES
-- ('alice', 'alice@example.com'),
-- ('bob', 'bob@example.com');
