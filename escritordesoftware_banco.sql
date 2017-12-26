CREATE TABLE `software` (
`id_software`	INTEGER PRIMARY KEY AUTOINCREMENT,
`nome_software`	TEXT
);
CREATE TABLE `objeto` (
`id_objeto`	INTEGER PRIMARY KEY AUTOINCREMENT,
`nome_objeto`	TEXT,
`id_software`	TEXT
);
CREATE TABLE `atributo` (
`id_atributo`	INTEGER PRIMARY KEY AUTOINCREMENT,
`nome_atributo`	TEXT,
`tipo_atributo`	TEXT,
`relacionamento_atributo`	TEXT,
`id_objeto`	INTEGER
);

