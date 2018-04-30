-- noinspection SqlDialectInspectionForFile

-- noinspection SqlNoDataSourceInspectionForFile

DROP PROCEDURE IF EXISTS findCharger;
DELIMITER //
CREATE PROCEDURE findCharger(lat DECIMAL(11,8), lng DECIMAL(11,8), charger_type INT(1))
BEGIN
  DECLARE EARTH_CIRCUMFERENCE INT(10);
  DECLARE radius DECIMAL(2,1);
  DECLARE minLat DECIMAL(11,8);
  DECLARE maxLat DECIMAL(11,8);
  DECLARE minLng DECIMAL(11,8);
  DECLARE maxLng DECIMAL(11,8);
  SET EARTH_CIRCUMFERENCE = 24901;
  SET radius = 0.1;
  SET minLat = lat - ((radius / EARTH_CIRCUMFERENCE) * 2 * PI() * (180 / PI()));
  SET maxLat = lat + ((radius / EARTH_CIRCUMFERENCE) * 2 * PI() * (180 / PI()));
  SET minLng = lng - ((radius / (COS(minLat * (PI()/180)) * EARTH_CIRCUMFERENCE)) * 2 * PI() * (180 / PI()));
  SET maxLng = lng + ((radius / (COS(minLat * (PI()/180)) * EARTH_CIRCUMFERENCE)) * 2 * PI() * (180 / PI()));
  IF (charger_type = 0) THEN
    SELECT charger_id
    FROM super_charger INNER JOIN charger USING (charger_id)
    WHERE (charger.lng > minLng and charger.lng < maxLng) and (charger.lat > minLat and charger.lat < maxLat);
  ELSE
    SELECT charger_id
    FROM destination_charger INNER JOIN charger USING (charger_id)
    WHERE (charger.lng > minLng and charger.lng < maxLng) and (charger.lat > minLat and charger.lat < maxLat);
  END IF;
END//

DELIMITER ;
DROP PROCEDURE IF EXISTS coordinateBox;
DROP PROCEDURE IF EXISTS testProcedure;