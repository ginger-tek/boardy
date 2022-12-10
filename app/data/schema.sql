CREATE TABLE IF NOT EXISTS users(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT,
  password TEXT,
  role TEXT DEFAULT `user`,
  isDeleted INTEGER DEFAULT 0,
  created TEXT DEFAULT CURRENT_TIMESTAMP,
  updated TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS posts(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  author INTEGER,
  title TEXT,
  details TEXT,
  tags TEXT,
  isDeleted INTEGER DEFAULT 0,
  isArchived INTEGER DEFAULT 0,
  created TEXT DEFAULT CURRENT_TIMESTAMP,
  updated TEXT DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_post_author FOREIGN KEY(author) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS comments(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  postId INTEGER,
  parentId INTEGER DEFAULT 0,
  author INTEGER,
  details TEXT,
  isDeleted INTEGER DEFAULT 0,
  created TEXT DEFAULT CURRENT_TIMESTAMP,
  updated TEXT DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_post_comment FOREIGN KEY(postId) REFERENCES posts(id) ON DELETE CASCADE
);

CREATE VIEW IF NOT EXISTS v_posts AS
  SELECT
    p.id,
    p.author,
    u.username as authorName,
    p.title,
    p.details,
    p.tags,
	  (SELECT COUNT(*) FROM comments WHERE postId = p.id) as numComments,
    p.isDeleted,
    p.isArchived,
    p.created,
    p.updated
  FROM posts p
  LEFT JOIN users u ON u.id == p.author;

CREATE VIEW IF NOT EXISTS v_comments AS
  SELECT
    c.id,
    c.postId,
    p.title as postTitle,
    c.parentId,
    c.author,
    u.username as authorName,
    c.details,
    c.isDeleted,
    c.created,
    c.updated
  FROM comments c
  LEFT JOIN users u ON u.id == c.author
  LEFT JOIN posts p ON p.id == c.postId;