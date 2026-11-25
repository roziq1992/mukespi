-- Fix IKP IDs so public incident submissions get unique primary keys.
-- Existing id_ikp values are preserved; MySQL continues from the current maximum.
SET @ikp_max_id = (SELECT COALESCE(MAX(id_ikp), 0) FROM ikp);
SET @ikp_zero_sql = CONCAT(
  'UPDATE ikp SET id_ikp = ',
  @ikp_max_id + 1,
  ' WHERE id_ikp = 0'
);
PREPARE ikp_zero_statement FROM @ikp_zero_sql;
EXECUTE ikp_zero_statement;
DEALLOCATE PREPARE ikp_zero_statement;

ALTER TABLE `ikp`
  MODIFY COLUMN `id_ikp` INT(5) NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `umur` VARCHAR(100) NOT NULL DEFAULT '',
  MODIFY COLUMN `jam_lapor` TIME NOT NULL DEFAULT '00:00:00';
