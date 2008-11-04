ALTER TABLE `zones` ADD `transfer` VARCHAR( 255 ) NULL AFTER `ttl` ;

INSERT INTO `options` (
	`prefkey` ,
	`preftype` ,
	`prefval`
)
VALUES (
	'transfer', 'normal', ''
);
