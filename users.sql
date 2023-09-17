CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(255) NOT NULL,
                       email VARCHAR(255) NOT NULL,
                       validts INT NOT NULL,
                       confirmed INT NOT NULL,
                       checked INT NOT NULL,
                       valid INT NOT NULL,
                       last_notification_stage TINYINT NOT NULL DEFAULT 0
);

CREATE INDEX idx_validts ON users (validts);
CREATE INDEX idx_filtering ON users (confirmed, checked, valid, last_notification_stage);

INSERT INTO users (username, email, validts, confirmed, checked, valid)
VALUES
    ('user1', 'user1@example.com', UNIX_TIMESTAMP(NOW() + INTERVAL 1 DAY), 1, 1, 1),
    ('user2', 'user2@example.com', UNIX_TIMESTAMP(NOW() + INTERVAL 3 DAY), 1, 1, 1),
    ('user3', 'user3@example.com', UNIX_TIMESTAMP(NOW() + INTERVAL 1 DAY), 1, 1, 1),
    ('user4', 'user4@example.com', UNIX_TIMESTAMP(NOW() + INTERVAL 2 DAY), 0, 1, 0),
    ('user5', 'user5@example.com', UNIX_TIMESTAMP(NOW() + INTERVAL 3 DAY), 1, 1, 1),
    ('user6', 'user6@example.com', UNIX_TIMESTAMP(NOW() + INTERVAL 1 DAY), 1, 0, 1),
    ('user7', 'user7@example.com', UNIX_TIMESTAMP(NOW() + INTERVAL 2 DAY), 1, 1, 0),
    ('user8', 'user8@example.com', UNIX_TIMESTAMP(NOW() + INTERVAL 1 DAY), 1, 1, 1),
    ('user9', 'user9@example.com', UNIX_TIMESTAMP(NOW() + INTERVAL 1 DAY), 0, 1, 0),
    ('user10', 'user10@example.com', UNIX_TIMESTAMP(NOW() + INTERVAL 3 DAY), 1, 1, 1);