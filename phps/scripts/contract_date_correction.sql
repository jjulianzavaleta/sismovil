SELECT termiesp_c_fechafin
FROM contract_solcontrato
GROUP BY termiesp_c_fechafin

SELECT *
FROM contract_solcontrato
WHERE termiesp_c_fechafin = '2022/10/2016'


UPDATE contract_solcontrato
SET termiesp_c_fechainicio = '2021/10/16', termiesp_c_fechafin = '2022/10/16'
WHERE id = 337