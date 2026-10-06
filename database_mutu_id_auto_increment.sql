-- Fix mutu_indikator IDs so single and range inserts get unique primary keys.
-- Existing id_mutu values are preserved; MySQL continues from the current maximum.
ALTER TABLE `mutu_indikator`
  MODIFY COLUMN `id_mutu` INT(11) NOT NULL AUTO_INCREMENT;