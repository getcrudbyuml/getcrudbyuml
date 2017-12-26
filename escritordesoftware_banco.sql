CREATE TABLE `objeto` (
`id`	INTEGER PRIMARY KEY AUTOINCREMENT,
`nome`	TEXT,
`idsoftware`	TEXT
);
CREATE TABLE `atributo` (
`id`	INTEGER PRIMARY KEY AUTOINCREMENT,
`nome`	TEXT,
`tipo`	TEXT,
`relacionamento`	TEXT,
`idobjeto`	TEXT
);
CREATE TABLE `software` (
`id`	INTEGER PRIMARY KEY AUTOINCREMENT,
`nome`	TEXT
);
