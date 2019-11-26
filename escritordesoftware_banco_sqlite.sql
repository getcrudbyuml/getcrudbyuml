CREATE TABLE `software` (
`id`	INTEGER PRIMARY KEY AUTOINCREMENT,
`nome`	TEXT
);
CREATE TABLE `objeto` (
`id`	INTEGER PRIMARY KEY AUTOINCREMENT,
`nome`	TEXT,
`idsoftware`	TEXT
);
CREATE TABLE `atributo` (
`id`	INTEGER PRIMARY KEY AUTOINCREMENT,
`nome`	TEXT,
`tipo`	TEXT,
`indice`	TEXT,
`idobjeto`	TEXT
);
CREATE TABLE `usuario` (
`id`	INTEGER PRIMARY KEY AUTOINCREMENT,
`nome`	TEXT,
`email`	TEXT,
`login`	TEXT,
`senha`	TEXT,
`nivel`	TEXT
);
