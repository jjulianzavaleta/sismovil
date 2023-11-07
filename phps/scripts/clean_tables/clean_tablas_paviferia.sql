DELETE FROM  paviferia_pedidodetalle;
DELETE FROM  paviferia_pedido;
DELETE FROM  paviferia_cliente;
DELETE FROM  paviferia_clientesdescuentos;
DELETE FROM  paviferia_precio;
DELETE FROM  paviferia_vendedor;
DELETE FROM  paviferia_zona;
DELETE FROM  paviferia_descuento;
DELETE FROM  paviferia_formapago;
DELETE FROM  paviferia_grupo;
DELETE FROM  paviferia_grupocliente;
DELETE FROM  paviferia_producto;

DBCC CHECKIDENT (paviferia_pedidodetalle, RESEED, 0);
DBCC CHECKIDENT (paviferia_pedido, RESEED, 0);
DBCC CHECKIDENT (paviferia_cliente, RESEED, 0);
DBCC CHECKIDENT (paviferia_clientesdescuentos, RESEED, 0);
DBCC CHECKIDENT (paviferia_precio, RESEED, 0);
DBCC CHECKIDENT (paviferia_descuento, RESEED, 0);