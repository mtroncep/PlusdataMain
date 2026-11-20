<?php
/**
 * Representa el la estructura de turnos
 * almacenadas en la base de datos
 */
require 'database.php';
class funciones
{   
    function __construct()
    {
    }

    public static function listaPaises()
    {
        $consulta = "SELECT *
            FROM paises 
            ORDER BY Descripcion
            ";
        try {
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            $comando->execute(array($consulta));
            return $comando->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
/*
    public static function obtenerVenta($empresa,$idVta)
    {   $consulta = "SELECT VTA_ID, VTA_USU_ID, VTA_COM_ID, VTA_FECHA, VTA_TURNO, VTA_VALOR_TURNO, 
                VTA_CANT_FOTOS, VTA_COMI_FOTOS, VTA_VALOR_NETO, VTA_VALOR_FOTO, VTA_COMI_FOTO,
                VTA_AGE_ID
                from ventas where VTA_EMP_ID=? and VTA_ID=?";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($empresa,$idVta));
            return $comando->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function listaTurnoActivoDia($empresa,$fecha,$usuario,$comercio)
    {
        $consulta = "select AGE_TURNO from agenda
                    where (AGE_EMP_ID = ? and AGE_FECHA = ? and AGE_USU_ID = ? and
                    AGE_COM_ID = ?) or 
                    (AGE_EMP_ID = ? and AGE_FECHA = ? and AGE_COM_ID = ?) or
                    (AGE_EMP_ID = ? and AGE_FECHA = ? and AGE_USU_ID=?)
                    ";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($empresa,$fecha,$usuario,$comercio,$empresa,$fecha,$comercio,$empresa,$fecha,$usuario));
            return $comando->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
                    
    }
    public static function hoy()
    {
        $consulta = "SELECT DATE_FORMAT(now(), '%Y%m%d') hoy";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();
            $salida=$comando->fetchAll(PDO::FETCH_ASSOC);
            return $salida[0]['hoy'];
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function listaComercios($empresa)
    {
        $consulta = "SELECT *
            FROM comercios 
            WHERE COM_EMP_ID = ?  
            ORDER BY COM_NOMBRE
            ";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($empresa));
            return $comando->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function listaAgendaActiva($empresa,$hoy)
    {
        $consulta = "SELECT AGE_ID, AGE_FECHA, USU_NOMBRE, COM_NOMBRE, AGE_TURNO,
                    COM_ID, COM_VALOR_FOTO_DIA, COM_COMI_FOTO_DIA, COM_VALOR_FOTO_NOCHE,
                    COM_COMI_FOTO_NOCHE, AGE_VTA_ID
                    FROM agenda, usuarios, comercios 
                    where AGE_EMP_ID= ? and AGE_FECHA>=? and USU_ID = AGE_USU_ID 
                    and COM_ID=AGE_COM_ID
                    group by age_id, age_turno
                    order by AGE_FECHA, usu_nombre, age_turno
                    ";

        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($empresa,$hoy));
            return $comando->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function listaAgendaUsuario($empresa,$fecha,$usuario)
    {
        $consulta = "SELECT AGE_FECHA, USU_NOMBRE, COM_NOMBRE, AGE_TURNO, AGE_ID, COM_ID,
        COM_VALOR_FOTO_DIA, COM_COMI_FOTO_DIA, COM_VALOR_FOTO_NOCHE, COM_COMI_FOTO_NOCHE,
        AGE_VTA_ID
        FROM agenda, `usuarios`, comercios
        where age_emp_id= ? and age_usu_id=? and age_fecha>=? and 
        usu_id = age_usu_id and com_id=age_com_id
        order by age_fecha, usu_nombre, age_turno
                    ";

        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($empresa,$usuario,$fecha));
            return $comando->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function listaComerciosAgenda($empresa,$fecha)
    {
        $consulta = "SELECT * from comercios
                    where COM_EMP_ID=? and COM_ID not in 
                    (SELECT AGE_COM_ID from agenda where AGE_FECHA=? and AGE_TURNO in ('D','N')) 
                    order by COM_NOMBRE";
                try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($empresa,$fecha));
            return $comando->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function listaComerciosAgendaNew($empresa,$fecha)
    {
        $consulta = "SELECT COUNT(com_id) QCOM from comercios
                    where COM_EMP_ID=? and 
                    (COM_ID in 
                    (SELECT AGE_COM_ID
                     from agenda
                     where AGE_FECHA=?
                     group by AGE_COM_ID
                     HAVING COUNT(AGE_com_id) < 2))
                    ";
                    
        $consulta_1 = "select * from comercios where COM_EMP_ID = ?";

        $consulta_2 = "SELECT * from comercios
                       where COM_EMP_ID=? and 
                       (COM_ID in 
                        (SELECT AGE_COM_ID
                         from agenda
                         where AGE_FECHA=?
                         group by AGE_COM_ID
                         HAVING COUNT(AGE_com_id) < 2))
                        ";
                        
        $consulta_3 = "select count(AGE_COM_ID) QAGE
                        from agenda
                        where AGE_EMP_ID=? and AGE_FECHA=?
                        ";
                        
        $consulta_4 = "SELECT * from comercios
                    where COM_EMP_ID=? and COM_ID not in 
                    (SELECT AGE_COM_ID from agenda where AGE_FECHA=? and AGE_TURNO in ('D','N')
                     group by AGE_COM_ID
                     HAVING count(AGE_COM_ID) = 2)";

        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            $comando->execute(array($empresa,$fecha));
            $qcom = $comando->fetchAll(PDO::FETCH_ASSOC);
            
            $comandoage = Database::getInstance()->getDb()->prepare($consulta_3);
            $comandoage->execute(array($empresa,$fecha));
            $qage = $comandoage->fetchAll(PDO::FETCH_ASSOC);
            
            if ($qcom[0]["QCOM"] == '0' && $qage[0]["QAGE"] == '0' ) {
                $comando = Database::getInstance()->getDb()->prepare($consulta_1);
                $comando->execute(array($empresa));
            } else {
                $comando = Database::getInstance()->getDb()->prepare($consulta_4);
                $comando->execute(array($empresa,$fecha));
            }
            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function verUsuarioDuplicado($empresa,$codigousuario)
    {
        $consulta = "SELECT USU_ID from usuarios where USU_CODIGO = ?";
        try {
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            $comando->execute(array($codigousuario));
            return $comando->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function listaUsuariosAgendaFecha($empresa,$fecha)
    {
        $consulta = "SELECT * from usuarios 
                    where USU_EMP_ID= ? and usu_perfil='U' and usu_id not in ( 
                    SELECT distinct AGE_USU_ID 
                    from agenda where age_fecha= ? group by age_usu_id 
                    having COUNT(age_USU_ID) = 2 or count(age_com_id) = 2)";

        try {
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            $comando->execute(array($empresa,$fecha));
            return $comando->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function listaUsuariosAgendaFechaUsuario($empresa,$fecha,$usuario)
    {
        $consulta = "SELECT * from usuarios 
                    where USU_EMP_ID= ? and usu_perfil='U' and usu_id not in (
                    SELECT distinct AGE_USU_ID 
                    from agenda where AGE_FECHA= ? group by AGE_USU_ID 
                    having COUNT(AGE_USU_ID) = 2 or count(AGE_COM_ID) = 2))";
        try {
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            $comando->execute(array($empresa,$fecha));
            return $comando->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }


    public static function controlIngresoUsuario($usuario,$clave)
    {
        $consulta = 
            "SELECT USU_EMP_ID, USU_CLAVE, USU_PERFIL, USU_CODIGO, EMP_NOMBRE, PAR_INI_PERIODO, PAR_FIN_PERIODO, 
            PAR_IMPORTE_TURNO, USU_ID, PAR_VALOR_FOTO_DIA, PAR_COMI_FOTO_DIA, PAR_VALOR_FOTO_NOCHE,
            PAR_COMI_FOTO_NOCHE, USU_NOMBRE, USU_VALOR_TURNO, USU_VALOR_FOTO_DIA, USU_COMI_FOTO_DIA,
            USU_VALOR_FOTO_NOCHE, USU_COMI_FOTO_NOCHE
            FROM usuarios, empresas, parametros
            WHERE USU_CODIGO = ?  AND EMP_ID = USU_EMP_ID AND PAR_ID=USU_EMP_ID
            ";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($usuario));
            return $comando->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function updatePeriodo($empresa,$fechaDesde,$fechaHasta,$importeTurno,$valorfotodia,
                                         $comifotodia,$valorfotonoche,$comifotonoche)
    {
        // Creando consulta UPDATE
        $consulta = "UPDATE parametros SET PAR_INI_PERIODO= ?, PAR_FIN_PERIODO= ?, PAR_IMPORTE_TURNO = ?,
            PAR_VALOR_FOTO_DIA = ?, PAR_COMI_FOTO_DIA = ?, PAR_VALOR_FOTO_NOCHE = ?,
            PAR_COMI_FOTO_NOCHE = ?
            WHERE PAR_EMP_ID= ?";

        // Preparar la sentencia
        $cmd = Database::getInstance()->getDb()->prepare($consulta);

        // Relacionar y ejecutar la sentencia
        $cmd->execute(array($fechaDesde, $fechaHasta, $importeTurno,$valorfotodia,$comifotodia,
                            $valorfotonoche,$comifotonoche,$empresa));
		
        return $cmd;
    }

    public static function updateVenta($empresa,$idvta,$cantfotos,$comifotos,$valorneto)
    {
        // Creando consulta UPDATE
        $consulta = "UPDATE ventas SET VTA_CANT_FOTOS= ?, VTA_COMI_FOTOS= ?, VTA_VALOR_NETO = ?
            WHERE VTA_EMP_ID= ? and VTA_ID= ?";

        // Preparar la sentencia
        $cmd = Database::getInstance()->getDb()->prepare($consulta);

        // Relacionar y ejecutar la sentencia
        $cmd->execute(array($cantfotos,$comifotos,$valorneto,$empresa,$idvta));
		
        return $cmd;
    }

    public static function updateComercio($empresa,$idcom,$nombre,$domicilio,$valorfotodia,
                                          $comifotodia,$valorfotonoche,$comifotonoche,
                                          $horariodia,$horarionoche)
    {
        // Creando consulta UPDATE
        $consulta = "UPDATE comercios SET COM_NOMBRE= ?, COM_DOMICILIO= ?, COM_VALOR_FOTO_DIA = ?,
            COM_COMI_FOTO_DIA = ?, COM_VALOR_FOTO_NOCHE = ?, COM_COMI_FOTO_NOCHE = ?,
            COM_HORARIO_DIA = ?, COM_HORARIO_NOCHE = ? 
            WHERE COM_ID= ?";

        // Preparar la sentencia
        $cmd = Database::getInstance()->getDb()->prepare($consulta);

        // Relacionar y ejecutar la sentencia
        $cmd->execute(array($nombre,$domicilio,$valorfotodia,
                            $comifotodia,$valorfotonoche,$comifotonoche,
                            $horariodia,$horarionoche,$idcom));
        return $cmd;
    }

    public static function updateUsuario($empresa,$id,$nombre,$domicilio,$valorfotodia,
                                         $email, $barrio, $celular,$valorturno,
                                         $comifotodia,$valorfotonoche,$comifotonoche)
    {
        // Creando consulta UPDATE
        $consulta = "UPDATE usuarios SET USU_NOMBRE=?, USU_DOMICILIO=?, USU_VALOR_FOTO_DIA=?,
            USU_COMI_FOTO_DIA=?, USU_VALOR_FOTO_NOCHE=?, USU_COMI_FOTO_NOCHE=?, 
            USU_EMAIL=?, USU_BARRIO=?, USU_CELULAR=?, USU_VALOR_TURNO=?
            WHERE USU_ID= ? AND USU_EMP_ID= ?" ;

        // Preparar la sentencia
        $cmd = Database::getInstance()->getDb()->prepare($consulta);

        // Relacionar y ejecutar la sentencia
        $cmd->execute(array($nombre,$domicilio,$valorfotodia,
                            $comifotodia,$valorfotonoche,$comifotonoche,
                            $email,$barrio,$celular,$valorturno,$id,$empresa));
        return $cmd;
    }

    public static function insertAgenda($empresa,$usuageid,$fecha,$comageid,$turno)
    {
        $consulta = "INSERT INTO agenda (AGE_EMP_ID,AGE_USU_ID,AGE_FECHA,AGE_COM_ID,AGE_TURNO) 
            VALUES(?,?,?,?,?)";

        // Preparar la sentencia
        $cmd = Database::getInstance()->getDb()->prepare($consulta);

        // Relacionar y ejecutar la sentencia
        $cmd->execute(array($empresa,$usuageid,$fecha,$comageid,$turno));
        return $cmd;
    }

    public static function insertUsuario($empresa,$nombre,$domicilio,$valorfotodia,
                                         $email, $barrio, $celular,$valorturno,
                                         $comifotodia,$valorfotonoche,$comifotonoche,$perfil,$clave,$codigo)
    {
        $consulta = "INSERT INTO usuarios (
            USU_NOMBRE,USU_DOMICILIO,USU_VALOR_FOTO_DIA,
            USU_COMI_FOTO_DIA,USU_VALOR_FOTO_NOCHE,USU_COMI_FOTO_NOCHE, 
            USU_EMAIL,USU_BARRIO,USU_CELULAR,USU_VALOR_TURNO,USU_PERFIL,USU_CLAVE,
            USU_EMP_ID,USU_CODIGO) 
            VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        // Preparar la sentencia
        $cmd = Database::getInstance()->getDb()->prepare($consulta);

        // Relacionar y ejecutar la sentencia
        $cmd->execute(array($nombre,$domicilio,$valorfotodia,
                            $comifotodia,$valorfotonoche,$comifotonoche,
                            $email,$barrio,$celular,$valorturno,$perfil,$clave,$empresa,$codigo));
        return $cmd;
    }

    public static function insertVenta($empresa,$usuid,$comid,$fecha,$turno,$valorturno,$cantfotos,$comi,$valorneto,$age_id,$valorfoto,$comifoto)
    {
        $consulta = "INSERT INTO ventas (
            VTA_EMP_ID,VTA_USU_ID,VTA_COM_ID,VTA_FECHA,VTA_TURNO,VTA_VALOR_TURNO,VTA_CANT_FOTOS,VTA_COMI_FOTOS,
            VTA_VALOR_NETO,VTA_AGE_ID,VTA_VALOR_FOTO,VTA_COMI_FOTO) 
            VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";

        $cmd = Database::getInstance()->getDb();
        $sentencia = $cmd->prepare($consulta);
        $sentencia->execute(array($empresa,$usuid,$comid,$fecha,$turno,$valorturno,$cantfotos,$comi,$valorneto,$age_id,$valorfoto,$comifoto));
		$vtaId = $cmd->lastInsertId();

        $agenda = "UPDATE agenda SET AGE_VTA_ID= ? WHERE AGE_ID= ?";
        $cmd = Database::getInstance()->getDb()->prepare($agenda);
        $cmd->execute(array($vtaId,$age_id));

        return $cmd;
    }

    public static function insertComercio(
        $empresa,
        $nombre,
        $domicilio,
        $valorfotodia,
        $comifotodia,
        $valorfotonoche,
        $comifotonoche
    )
    {
        // Sentencia INSERT
        $comando = "INSERT INTO comercios ( " .
            " COM_EMP_ID," .
            " COM_NOMBRE," .
            " COM_DOMICILIO," .
            " COM_VALOR_FOTO_DIA," .
            " COM_COMI_FOTO_DIA," .
            " COM_VALOR_FOTO_NOCHE," .
            " COM_COMI_FOTO_NOCHE)" .
            " VALUES(?,?,?,?,?,?,?)";
			
		// Preparar la sentencia
        $pdo = Database::getInstance()->getDb();
        $sentencia = $pdo->prepare($comando);
		$sentencia->execute(array($empresa,$nombre,$domicilio,$valorfotodia,$comifotodia,$valorfotonoche,$comifotonoche));
		return $pdo->lastInsertId();
    }
    
    public static function deleteEntradaAgenda($empresa, $id)
    {
        // Sentencia DELETE
        $comando = "DELETE FROM agenda WHERE AGE_ID=? AND AGE_EMP_ID=?";

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);
        return $sentencia->execute(array($id,$empresa));
    }
	
    public static function deleteVenta($empresa, $idAge, $idVta)
    {
        // Para borrar la Venta...
        $comandoVta = "DELETE FROM ventas WHERE VTA_ID=? AND VTA_EMP_ID=?";

        // Para sacar la venta de la agenda...
        $comandoAge = "UPDATE agenda set AGE_VTA_ID = 0 WHERE AGE_ID=? AND AGE_EMP_ID=?";

        // Preparar la sentencia delete
        $sentenciaVta = Database::getInstance()->getDb()->prepare($comandoVta);
        $sentenciaVta->execute(array($idVta,$empresa));

        // Preparar la sentencia update
        $sentenciaAge = Database::getInstance()->getDb()->prepare($comandoAge);
        return $sentenciaAge->execute(array($idAge,$empresa));
    }
	
    public static function deleteComercio($empresa, $id)
    {
        // Sentencia DELETE
        $comando = "DELETE FROM comercios WHERE COM_ID=? AND COM_EMP_ID=?";

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);
        return $sentencia->execute(array($id,$empresa));
    }
	
    public static function deleteUsuario($empresa, $id)
    {
        // Sentencia DELETE
        $comando = "DELETE FROM usuarios WHERE USU_ID=? AND USU_EMP_ID=?";

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);
        return $sentencia->execute(array($id,$empresa));
    }
	
	public static function totalVentaPeriodoUsuario($empresa,$usuario,$fechaini,$fechafin)
    {
        // Sentencia SELECT
		$comando = "SELECT sum(VTA_VALOR_TURNO) SUE, sum(VTA_COMI_FOTOS) COMI, 
		                   sum(VTA_VALOR_TOTAL) EMP 
		              from ventas 
		              where VTA_EMP_ID=? and VTA_USU_ID=? and VTA_FECHA >=? and VTA_FECHA<=?";
        // Preparar la sentencia...
        $sentencia = Database::getInstance()->getDb()->prepare($comando);
        $sentencia->execute(array($empresa,$usuario,$fechaini,$fechafin));
        $importes = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        return $importes;
    }
    
	public static function listaVentaPeriodoUsuario($empresa,$usuario,$fechaini,$fechafin)
    {
        // Sentencia SELECT
		$comando = "SELECT VTA_ID, USU_ID, USU_NOMBRE, COM_ID, COM_NOMBRE, VTA_FECHA, VTA_TURNO, 
		            VTA_VALOR_TURNO, VTA_CANT_FOTOS, VTA_COMI_FOTO, VTA_COMI_FOTOS, 
		            VTA_VALOR_FOTO, VTA_VALOR_NETO, AGE_ID
                    FROM ventas, comercios, usuarios, agenda
                    where VTA_EMP_ID=? and VTA_USU_ID=? and VTA_FECHA >=? AND VTA_FECHA <=? AND
                    USU_ID=VTA_USU_ID and COM_ID=VTA_COM_ID and AGE_ID=VTA_AGE_ID
                    order by VTA_COM_ID, VTA_FECHA, VTA_TURNO";
        // Preparar la sentencia...
        $sentencia = Database::getInstance()->getDb()->prepare($comando);
        $sentencia->execute(array($empresa,$usuario,$fechaini,$fechafin));
        $importes = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        return $importes;
    }

    
	public static function listaVentaPeriodoAdministrador($empresa,$fechaini,$fechafin)
    {
        // Sentencia SELECT
		$comando = "SELECT VTA_ID, USU_ID, USU_NOMBRE, COM_ID, COM_NOMBRE, VTA_FECHA, VTA_TURNO, 
		            VTA_VALOR_TURNO, VTA_CANT_FOTOS, VTA_COMI_FOTO, VTA_COMI_FOTOS, 
		            VTA_VALOR_FOTO, VTA_VALOR_NETO, AGE_ID
                    FROM ventas, comercios, usuarios, agenda
                    where VTA_EMP_ID=? and VTA_FECHA >=? AND VTA_FECHA <=? AND
                    USU_ID=VTA_USU_ID and COM_ID=VTA_COM_ID and AGE_ID=VTA_AGE_ID
                    order by VTA_COM_ID, VTA_USU_ID, VTA_FECHA, VTA_TURNO";
        // Preparar la sentencia...
        $sentencia = Database::getInstance()->getDb()->prepare($comando);
        $sentencia->execute(array($empresa,$fechaini,$fechafin));
        $importes = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        return $importes;
    }
  

    
    public static function obtenerNombresUnicos()
    {   
        $consulta = "SELECT DISTINCT TURNOS_NOMBRE FROM turnos ORDER BY TURNOS_NOMBRE";
        try{
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            $comando->execute();    
            $nombres = $comando->fetchAll(PDO::FETCH_ASSOC);
            return $nombres;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getAllTurnos()
    {
        $consulta = "SELECT * FROM turnos ORDER BY TURNOS_NOMBRE, TURNOS_FECHA ASC, TURNOS_HORA ASC";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();
            return $comando->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
	
	    public static function obtenerTurnosPeriodo($desde, $hasta, $estado)
    {
		if ($estado <> "P") {
        	$consulta = "SELECT * FROM turnos where TURNOS_FECHA >= ? and TURNOS_FECHA<= ? and TURNOS_IMPORTE <> 0
		             ORDER BY TURNOS_FECHA, TURNOS_HORA";
		} else {
        	$consulta = "SELECT * FROM turnos where TURNOS_FECHA >= ? and TURNOS_FECHA<= ?
		             and TURNOS_IMPORTE = 0 ORDER BY TURNOS_FECHA, TURNOS_HORA";
		}
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($desde, $hasta));
            return $comando->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
	}

	    public static function obtenerTurnosMes($desde, $hasta)
    {
        $consulta = "SELECT * FROM turnos where TURNOS_FECHA >= ? and TURNOS_FECHA <= ? 
            ODER BY TURNOS_FECHA, TURNOS_HORA";
        try {
        // Preparar sentencia
        $comando = Database::getInstance()->getDb()->prepare($consulta);
        // Ejecutar sentencia preparada
        $comando->execute(array($desde, $hasta));
        return $comando->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
	}
    
    public static function getTurnoById($idTurno)
    {
        // Consulta del turno
        $consulta = "SELECT TURNOS_ID,
                            TURNOS_FECHA,
                            TURNOS_HORA,
                            TURNOS_NOMBRE,
                            TURNOS_IMPORTE
                            FROM turnos
                            WHERE TURNOS_ID = ?";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($idTurno));
            // Capturar primera fila del resultado
            $row = $comando->fetch(PDO::FETCH_ASSOC);
            return $row;

        } catch (PDOException $e) {
            // Aqu铆 puedes clasificar el error dependiendo de la excepci贸n
            // para presentarlo en la respuesta Json
            return -1;
        }
    }
    
    public static function getTurnosByDate($fecha, $estado)
    {
        // Consulta del turno
        if ($estado == "C")
        { $consulta = "SELECT TURNOS_ID,TURNOS_FECHA,TURNOS_HORA,TURNOS_NOMBRE,TURNOS_IMPORTE FROM turnos WHERE TURNOS_FECHA = ? AND  TURNOS_IMPORTE <> 0 ORDER BY TURNOS_HORA";
        } else
        { $consulta = "SELECT TURNOS_ID,TURNOS_FECHA,TURNOS_HORA,TURNOS_NOMBRE,TURNOS_IMPORTE FROM turnos WHERE TURNOS_FECHA = ? AND TURNOS_IMPORTE = 0 ORDER BY TURNOS_HORA";
        }
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($fecha));
            // Capturar primera fila del resultado
            $row = $comando->fetchAll(PDO::FETCH_ASSOC);
            return $row;

        } catch (PDOException $e) {
            // Aqu铆 puedes clasificar el error dependiendo de la excepci贸n
            // para presentarlo en la respuesta Json
            return -1;
        }
    }
    
    public static function updateTurno(
        $idTurno,
        $fecha,
        $hora,
        $nombre,
        $importe
    )
    {
        // Creando consulta UPDATE
        $consulta = "UPDATE turnos" .
            " SET TURNOS_FECHA=?, TURNOS_HORA=?, TURNOS_NOMBRE=?, TURNOS_IMPORTE=? " .
            "WHERE TURNOS_ID=?";

        // Preparar la sentencia
        $cmd = Database::getInstance()->getDb()->prepare($consulta);

        // Relacionar y ejecutar la sentencia
        $cmd->execute(array($fecha, $hora, $nombre, $importe, $idTurno));
		
        return $cmd;
    }
    
    public static function insertTurno(
        $fecha,
        $hora,
        $nombre,
        $importe
    )
    {
        // Sentencia INSERT
        $comando = "INSERT INTO turnos ( " .
            " TURNOS_FECHA," .
            " TURNOS_HORA," .
            " TURNOS_NOMBRE," .
            " TURNOS_IMPORTE)" .
            " VALUES( ?,?,?,?)";
			
		// Preparar la sentencia
        $pdo = Database::getInstance()->getDb();
        $sentencia = $pdo->prepare($comando);
		$sentencia->execute(array($fecha,$hora,$nombre,$importe));
		return $pdo->lastInsertId();
    }
    
    public static function getTurnoID()
    {
		$rs = "SELECT MAX(id_tabla) AS id FROM tabla";
		$turnoID = Database::getInstance()->getDb()->prepare($rs);
        // Ejecutar sentencia preparada
        $turnoID->execute();
        $nroROW = $turnoID->fetch(PDO::FETCH_ASSOC);
		return $nroROW;
    }
    
    public static function deleteTurno($idTurno)
    {
        // Sentencia DELETE
        $comando = "DELETE FROM turnos WHERE TURNOS_ID=?";

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute(array($idTurno));
    }
	
	public static function totalDelMes()
    {
        // Sentencia SELECT
		$comando = "SELECT SUM(TURNOS_IMPORTE) as importe FROM `turnos` WHERE ".
		"TURNOS_FECHA >= (SELECT FECHA_INICIO FROM periodo) AND ".
		"TURNOS_FECHA <= (SELECT FECHA_FIN FROM periodo)";
        // Preparar la sentencia...
        $sentencia = Database::getInstance()->getDb()->prepare($comando);
        $sentencia->execute();
        $importe = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        return $importe;
    }
    
	public static function totalPorMeses()
    {
        // Sentencia SELECT
        $comando = "SELECT LEFT(TURNOS_FECHA,6) MES, SUM(`TURNOS_IMPORTE`) IMPORTE FROM `turnos` WHERE 1 
            GROUP BY LEFT(TURNOS_FECHA,6)
            ORDER BY LEFT(TURNOS_FECHA,6) DESC";
        // Preparar la sentencia...
        $sentencia = Database::getInstance()->getDb()->prepare($comando);
        $sentencia->execute();
        $importes = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        return $importes;
    }
    
	public static function totalDeUnDia($fecha)
    {
        // Sentencia SELECT
		$comando = "SELECT SUM(TURNOS_IMPORTE) as importe FROM turnos WHERE ".
		"TURNOS_FECHA = ?";
        // Preparar la sentencia...
        $sentencia = Database::getInstance()->getDb()->prepare($comando);
        $sentencia->execute(array($fecha));
        $importe = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        return $importe;
    }

    	public static function totalDeUnMes($mes)
    {
        // Sentencia SELECT
        $fDesde=$mes . "01";
        $fHasta=$mes . "31";
		$comando = "SELECT SUM(TURNOS_IMPORTE) as importe FROM turnos WHERE ".
		"TURNOS_FECHA >= ? and TURNOS_FECHA <= ?";
        // Preparar la sentencia...
        $sentencia = Database::getInstance()->getDb()->prepare($comando);
        $sentencia->execute(array($fDesde, $fHasta));
        $importe = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        return $importe;
    }
    
    public static function getPeriodoIni()
    {
        $consulta = "SELECT * FROM periodo";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();
            $periodo=$comando->fetchAll(PDO::FETCH_ASSOC);
            
            $inicio = json_decode(json_encode($periodo));
            return $inicio[0]->FECHA_INICIO;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function getPeriodoFin()
    {
        $consulta = "SELECT * FROM periodo";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();
            $periodo=$comando->fetchAll(PDO::FETCH_ASSOC);
            
            $inicio = json_decode(json_encode($periodo));
            return $inicio[0]->FECHA_FIN;
        } catch (PDOException $e) {
            return false;
        }
    }
*/    
}
?>