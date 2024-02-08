--
-- Solo necesario eje
--
UPDATE `ticket`
JOIN `venta` ON `venta`.`IdVenta` = `ticket`.`IdVenta`
SET `ticket`.AnoTicket = YEAR(`venta`.`FechaVenta`);