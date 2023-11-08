USE db_sismovil;

declare @counterOrigin_contract_solcontrato as INT
declare @counterTemp_contract_solcontrato as INT
declare @counterTarget_contract_solcontrato as INT

set @counterOrigin_contract_solcontrato = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_solcontrato'));

if(OBJECT_ID('tmp_contract_solcontrato') IS NOT NULL)
    DROP TABLE tmp_contract_solcontrato;

DELETE FROM contract_solcontrato;

SELECT *
INTO tmp_contract_solcontrato
FROM OPENQUERY(PGRESS, 'SELECT id, reqgen_a_empresa, reqgen_a_areasolicitante, reqgen_a_areasolicitante_jefatura, reqgen_a_compradorresponsable, reqgen_a_areausuaria, reqgen_a_areausuaria_jefatura, reqgen_proveedor, reqgen_proveedor_ruc, termiesp_a_tipocontrato, termiesp_a_nrocotizacion, termiesp_a_fecha, termiesp_b_alcance, termiesp_c_dias, termiesp_c_medida, termiesp_c_fechainicio, termiesp_c_fechafin, termiesp_c_incluyeacta, termiesp_d_monto, termiesp_d_moneda, termiesp_e_formapago, termiesp_e_avancez_medida, termiesp_e_credito_dias, termiesp_f_modalidadpago, termiesp_g_garantia, termiesp_g_adelanto_importe, termiesp_g_fcumplimiento_importe, termiesp_g_fondogarantia_importe, termiesp_h_lugarentrega, termiesp_i_observacionesamplicaciones, reqesp_ruta, autorizac_a_nombres, autorizac_a_cargo, autorizac_a_fecha, autorizac_b_nombres, autorizac_b_cargo, autorizac_b_fecha, datosgenerales_fecharegistra, datosgenerales_usuarioregistra, datosgenerales_fechaactualiza, datosgenerales_usuarioactualiza, datosgenerales_estado, datosgenerales_codigo, datosgenerales_urlcontrato, jur_file_ficharuc, jur_file_represetante, jur_file_vigenciapoder, nat_file_ficharuc, nat_file_represetante, proveedor_tipo, anulado, anulado_usuario, anulado_fecha, waitfirmamodo, tipo_renovacion, procesado, contrato_vinculado, procesado_time, termiesp_c_formato, modalidadpago_otro, contrato_propuesto_proveedor, tipocontrato_otrosdesc, modalidadpago_cartafianza_importe, modalidadpago_adelanto_adelantofile, modalidadpago_adelanto_exception, lugar_entrega_personal_tercero_numero, lugar_entrega_personal_tercero_dias, lugar_entrega_personal_tercero_equipo, metas_cumplir_comentario, metas_cumplir_entregables, rubro_inmuebles_partidaregistral, lugar_entrega_personal_tercero, monto_mobiliario, autorizac_c_nombres, autorizac_c_cargo, autorizac_c_fecha, tipo_flujo, flag_has_last_approved_usuario, flag_has_last_approved_logistica, formapago_medida, modalidadpago_cartafianza_medida, modalidadpago_adelanto_medida, modalidadpago_fcumplimiento_medida, modalidadpago_fgarantia_medida, monto_mobiliario_medida, penalidades_medida, contraprestacion_incdocumento, contraprestacion_file, cotizacion_bynrocontrato from sismovil.public.contract_solcontrato');


set @counterTemp_contract_solcontrato = (SELECT COUNT(*) FROM tmp_contract_solcontrato);

if(  @counterOrigin_contract_solcontrato = @counterTemp_contract_solcontrato )
    print('contract_solcontrato table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_solcontrato table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_solcontrato ON;

INSERT INTO contract_solcontrato(id, reqgen_a_empresa, reqgen_a_areasolicitante, reqgen_a_areasolicitante_jefatura, reqgen_a_compradorresponsable, reqgen_a_areausuaria, reqgen_a_areausuaria_jefatura, reqgen_proveedor, reqgen_proveedor_ruc, termiesp_a_tipocontrato, termiesp_a_nrocotizacion, termiesp_a_fecha, termiesp_b_alcance, termiesp_c_dias, termiesp_c_medida, termiesp_c_fechainicio, termiesp_c_fechafin, termiesp_c_incluyeacta, termiesp_d_monto, termiesp_d_moneda, termiesp_e_formapago, termiesp_e_avancez_medida, termiesp_e_credito_dias, termiesp_f_modalidadpago, termiesp_g_garantia, termiesp_g_adelanto_importe, termiesp_g_fcumplimiento_importe, termiesp_g_fondogarantia_importe, termiesp_h_lugarentrega, termiesp_i_observacionesamplicaciones, reqesp_ruta, autorizac_a_nombres, autorizac_a_cargo, autorizac_a_fecha, autorizac_b_nombres, autorizac_b_cargo, autorizac_b_fecha, datosgenerales_fecharegistra, datosgenerales_usuarioregistra, datosgenerales_fechaactualiza, datosgenerales_usuarioactualiza, datosgenerales_estado, datosgenerales_codigo, datosgenerales_urlcontrato, jur_file_ficharuc, jur_file_represetante, jur_file_vigenciapoder, nat_file_ficharuc, nat_file_represetante, proveedor_tipo, anulado, anulado_usuario, anulado_fecha, waitfirmamodo, tipo_renovacion, procesado, contrato_vinculado, procesado_time, termiesp_c_formato, modalidadpago_otro, contrato_propuesto_proveedor, tipocontrato_otrosdesc, modalidadpago_cartafianza_importe, modalidadpago_adelanto_adelantofile, modalidadpago_adelanto_exception, lugar_entrega_personal_tercero_numero, lugar_entrega_personal_tercero_dias, lugar_entrega_personal_tercero_equipo, metas_cumplir_comentario, metas_cumplir_entregables, rubro_inmuebles_partidaregistral, lugar_entrega_personal_tercero, monto_mobiliario, autorizac_c_nombres, autorizac_c_cargo, autorizac_c_fecha, tipo_flujo, flag_has_last_approved_usuario, flag_has_last_approved_logistica, formapago_medida, modalidadpago_cartafianza_medida, modalidadpago_adelanto_medida, modalidadpago_fcumplimiento_medida, modalidadpago_fgarantia_medida, monto_mobiliario_medida, penalidades_medida, contraprestacion_incdocumento, contraprestacion_file, cotizacion_bynrocontrato)
SELECT id, reqgen_a_empresa, reqgen_a_areasolicitante, reqgen_a_areasolicitante_jefatura, reqgen_a_compradorresponsable, reqgen_a_areausuaria, reqgen_a_areausuaria_jefatura, reqgen_proveedor, reqgen_proveedor_ruc, termiesp_a_tipocontrato, termiesp_a_nrocotizacion, termiesp_a_fecha, termiesp_b_alcance, termiesp_c_dias, termiesp_c_medida, termiesp_c_fechainicio, termiesp_c_fechafin, termiesp_c_incluyeacta, termiesp_d_monto, termiesp_d_moneda, termiesp_e_formapago, termiesp_e_avancez_medida, termiesp_e_credito_dias, termiesp_f_modalidadpago, termiesp_g_garantia, termiesp_g_adelanto_importe, termiesp_g_fcumplimiento_importe, termiesp_g_fondogarantia_importe, termiesp_h_lugarentrega, termiesp_i_observacionesamplicaciones, reqesp_ruta, autorizac_a_nombres, autorizac_a_cargo, autorizac_a_fecha, autorizac_b_nombres, autorizac_b_cargo, autorizac_b_fecha, datosgenerales_fecharegistra, datosgenerales_usuarioregistra, datosgenerales_fechaactualiza, datosgenerales_usuarioactualiza, datosgenerales_estado, datosgenerales_codigo, datosgenerales_urlcontrato, jur_file_ficharuc, jur_file_represetante, jur_file_vigenciapoder, nat_file_ficharuc, nat_file_represetante, proveedor_tipo, anulado, anulado_usuario, anulado_fecha, waitfirmamodo, tipo_renovacion, procesado, contrato_vinculado, procesado_time, termiesp_c_formato, modalidadpago_otro, contrato_propuesto_proveedor, tipocontrato_otrosdesc, modalidadpago_cartafianza_importe, modalidadpago_adelanto_adelantofile, modalidadpago_adelanto_exception, lugar_entrega_personal_tercero_numero, lugar_entrega_personal_tercero_dias, lugar_entrega_personal_tercero_equipo, metas_cumplir_comentario, metas_cumplir_entregables, rubro_inmuebles_partidaregistral, lugar_entrega_personal_tercero, monto_mobiliario, autorizac_c_nombres, autorizac_c_cargo, autorizac_c_fecha, tipo_flujo, flag_has_last_approved_usuario, flag_has_last_approved_logistica, formapago_medida, modalidadpago_cartafianza_medida, modalidadpago_adelanto_medida, modalidadpago_fcumplimiento_medida, modalidadpago_fgarantia_medida, monto_mobiliario_medida, penalidades_medida, contraprestacion_incdocumento, contraprestacion_file, cotizacion_bynrocontrato FROM tmp_contract_solcontrato;

SET IDENTITY_INSERT dbo.contract_solcontrato OFF;

update contract_solcontrato set datosgenerales_fecharegistra =SUBSTRING (datosgenerales_fecharegistra,0,23);
update contract_solcontrato set datosgenerales_fechaactualiza =SUBSTRING (datosgenerales_fechaactualiza,0,23);

set @counterTarget_contract_solcontrato = (SELECT COUNT(*) FROM contract_solcontrato);

if(  @counterOrigin_contract_solcontrato = @counterTarget_contract_solcontrato )
    print('contract_solcontrato table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_solcontrato table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_solcontrato;