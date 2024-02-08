--
-- Ajuste para conservar numero de ticket cuando una venta se borra
--
UPDATE `ticket`
JOIN `venta` ON `venta`.`IdVenta` = `ticket`.`IdVenta`
SET `ticket`.AnoTicket = YEAR(`venta`.`FechaVenta`);

--
-- Ajuste para conservar numeros de presupuesto cuando un presupuesto se borra
--
INSERT into numeropresupuesto (select IdPresupuesto, IdPresupuesto, YEAR(Fecha) from presupuesto);
