<?php
/**
 * 
 * @author Jefferson Uchoa Ponte
 * Ferramenta para programador para facilitar manipulação de banco de dados postgres. 
 *
 */
class AdminPG{
	public static function main(){
		
		$dao = new DAO();
		$conexao = $dao->getConexao();
		
		$sql = "SELECT schemaname AS esquema, tablename AS tabela, tableowner AS dono 
				FROM pg_catalog.pg_tables
				WHERE schemaname NOT IN ('pg_catalog', 'information_schema', 'pg_toast')
				ORDER BY schemaname, tablename";
		$result = $conexao->query($sql);
		
		foreach($result as $linha){
		
		
			$nomeDaTabela =  $linha['tabela'];
			echo '<h1>'.$nomeDaTabela.'</h1>';
			$sqlColunas = "select
			c.relname,
			a.attname as column,
			pg_catalog.format_type(a.atttypid, a.atttypmod) as datatype
		
			from pg_catalog.pg_attribute a
			inner join pg_stat_user_tables c on a.attrelid = c.relid
			WHERE
			c.relname = '$nomeDaTabela' AND
			a.attnum > 0
			AND NOT a.attisdropped
			";
			$resultDasColunas = $dao->getConexao()->query($sqlColunas);
			foreach ($resultDasColunas as $linhaDasColunas){
				echo $linhaDasColunas['column'].' | '.$linhaDasColunas['datatype'].'<br>';
			}
			
			$sqlPK = "SELECT a.attname AS chave_pk
            FROM pg_class c
              INNER JOIN pg_attribute a ON (c.oid = a.attrelid)
              INNER JOIN pg_index i ON (c.oid = i.indrelid)
            WHERE
              i.indkey[0] = a.attnum AND
              i.indisprimary = 't' AND
              c.relname = '$nomeDaTabela'";
			
			$resultPK = $dao->getConexao()->query($sqlPK);
			foreach($resultPK as $linhaPK){
			    echo '<p>PK: <b>'.$linhaPK['chave_pk'].'</b></p>';
			}
			
			$sqlChaves = "SELECT   
            a.attname AS atributo,   
            clf.relname AS tabela_ref,   
            af.attname AS atributo_ref   
            FROM pg_catalog.pg_attribute a   
            JOIN pg_catalog.pg_class cl ON (a.attrelid = cl.oid AND cl.relkind = 'r')
            JOIN pg_catalog.pg_namespace n ON (n.oid = cl.relnamespace)   
            JOIN pg_catalog.pg_constraint ct ON (a.attrelid = ct.conrelid AND   
            ct.confrelid != 0 AND ct.conkey[1] = a.attnum)   
            JOIN pg_catalog.pg_class clf ON (ct.confrelid = clf.oid AND clf.relkind = 'r')
            JOIN pg_catalog.pg_namespace nf ON (nf.oid = clf.relnamespace)   
            JOIN pg_catalog.pg_attribute af ON (af.attrelid = ct.confrelid AND   
            af.attnum = ct.confkey[1])   
            WHERE   
            cl.relname = '$nomeDaTabela'";
			$resultChaves = $dao->getConexao()->query($sqlChaves);
			foreach($resultChaves as $linhaChaves){
			    echo '<p>FK: <b>'.$linhaChaves['atributo'].' - '.$linhaChaves['tabela_ref'].'('.$linhaChaves['atributo_ref'].')'.'</b></p>';
			}
			
			$n = 10;
		
			echo '<br>'.$n.' primeiros dados<br>';
			$sqlPrimeirosDados = "SELECT * FROM $nomeDaTabela LIMIT $n";
			$resultPrimeirosDados = $dao->getConexao()->query($sqlPrimeirosDados);
			$i = 0;
			echo '<table border=1>';
			foreach ($resultPrimeirosDados as $linhaPrimeirosDados){
		
				if(!$i){
					echo '<tr>';
					foreach ($linhaPrimeirosDados as $chave => $valor){
						if(!is_int($chave))
							echo '<th>'.$chave.'</th>';
					}
					echo '</tr>';
					$i++;
		
				}
				echo '<tr>';
				foreach ($linhaPrimeirosDados as $chave => $valor){
					if(!is_int($chave))
						echo '<td>'.$valor.'</td>';
				}
				echo '</tr>';
		
			}
			echo '</table>';
		
			echo '<hr>';
		
		
		
		}
		
		echo '<br><br>';
		
	}	

    public static function criarTabelas($strFileSql){
	    $dao = new DAO();
	    
	    
	    $arquivo = fopen ($strFileSql, 'r');
	    $conteudo = "";
	    while(!feof($arquivo)){
	        $conteudo .= fgets($arquivo, 1024);
	        
	    }
	    
	    $lista = explode(";", $conteudo);
	    foreach($lista as $sql){
	        if($sql != ""){
	            echo $sql.'<br><hr>';
	        }
	        if($dao->getConexao()->exec($sql)){
	            echo "Sucesso";
	        }else{
	            echo "Fracasso";
	        }
	        
	    }
	    fclose($arquivo);
	}
	
	
}