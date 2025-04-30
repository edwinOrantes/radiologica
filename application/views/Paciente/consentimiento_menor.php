<style>

    *{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }
    #cabecera {
        width: 100%;
        margin: auto;
        }

    #lateral {
        float:left; /* Aquí determinas de lado quieres quede esta "columna" */
        width: 35%;  /* Este será el ancho que tendrá tu columna */
    }

    #principal {
        float: right;
        text-align: center;
        width: 59%;
    }

    #principal table {
        width: 100%;
        text-align: center;
    }
        
    /* Para limpiar los floats */
    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }

    .contenedor{
        font-family: "Times New Roman", Times, serif;
        font-size: 16px;
        line-height: 25px;
        text-align: justify;
    }

    .resaltar{
        text-decoration: underline;
        font-weight: bold
    }

    .pie{
        width: 100%;
        }
    .pie_izquierda{
        width: 50%;
        float: left;
    }
    .pie_derecha{
        float: right;
        text-align: left;
        width: 50%;
    }

</style>
<div class="contenedor">
    <?php $espacios = "&nbsp;&nbsp;&nbsp;"; ?>                    
<p>
YO <span class="resaltar"> <?php echo $espacios.$responsable->nombreResponsable.$espacios; ?> </span> de <span class="resaltar"> 
<?php echo $espacios.$responsable->edadResponsable.$espacios; ?> </span> años de edad, profesión <span class="resaltar"> 
<?php echo $espacios.$responsable->profesionResponsable.$espacios; ?> </span>, del domicilio de <span class="resaltar">
<?php echo $espacios.$responsable->direccionResponsable.$espacios; ?> </span>, 
y con Documento Único de Identidad número <span class="resaltar"> <?php echo $espacios.$responsable->duiResponsable.$espacios; ?> </span> 
BAJO JURAMENTO DECLARO QUE: Que en el ejercicio de mis derechos protegidos en los artículos uno, dos y sesenta y cinco de la Constitución de la República de El Salvador, 
Artículo 9 literal L), 13 y 15 de la “ Ley de Deberes y Derechos de los Pacientes y Prestadores de Servicios de Salud” , Tratados Internacionales y en pleno uso de mis 
facultades mentales, he leído y comprendido el presente documento, habiendo solicitado voluntariamente los servicios profesionales del 
Doctor (a) <span class="resaltar"> <?php echo $espacios.$paciente->nombreMedico.$espacios; ?> </span> ,
de_________ años de edad ____________________________ ( Especialidad del Doctor); del domicilio de ___________________________________ Departamento de _____________________;
con Documento Único de Identidad _________________________. Y los servicios hospitalarios de la Sociedad “ UNION MEDICA, SOCIEDAD ANOMIMA DE CAPITAL VARIABLE” que se abrevia “ 
UNION MEDICA, S.A DE C.V.” (HOSPITAL CLINICA ORELLANA) de este domicilio, con Número de Identificación Tributaria cero seiscientos catorce guion doscientos cuarenta mil quinientos 
dieciséis guion ciento dos guion nueve, y encontrándome en pleno uso de mis facultades mentales y con plena capacidad legal de autorización, por medio del presente documento 
DOY MI CONSETIMIENTO EXPRESO para la hospitalización y realización de procedimientos médicos y/o quirúrgicos que sean necesarios a mí: <span class="resaltar"> <?php echo $espacios.$responsable->esResponsable.$espacios; ?> </span> , 
<span class="resaltar"> <?php echo $espacios.$paciente->nombrePaciente.$espacios; ?> </span>
menor de edad,   bajo las siguientes especificaciones:</p>

a) RECONOZCO que he sido debidamente informado(a) en lenguaje comprensible del  estado de salud habiéndole diagnosticado
<hr><hr>
<p>Diagnóstico por el cual según me informa el médico tratante se requiere de hospitalización y de 
procedimientos médicos y/o quirúrgicos, los cuales yo he seleccionado dentro de un grupo de alternativas terapéuticas que he discutido con el médico tratante y AUTORIZO para la realización de dicho 
tratamiento y/o procedimiento comprendiendo los riesgos y beneficios que dichos procedimientos conllevan. El procedimiento o tratamiento a realizarse es el siguiente________________________________________________________ asimismo se me ha explicado  que los riesgos y complicaciones que puedan surgir durante el diagnóstico y tratamiento son inherente a la enfermedad misma o a los procedimientos médicos
o quirúrgicos que se realicen y soy consciente que el tratamiento de esta enfermedad misma o los procedimientos quirúrgicos que se realicen y soy consciente que el tratamiento de estas complicaciones 
requiere gastos adicionales por lo que  DOY AQUÍ MI EXPRESA AUTORIZACION para que realicen cualquier otro procedimiento adicional que requieran estas complicaciones derivadas o cambios de plan terapéuticos 
emergentes.</p>

<p>b) CONSIENTO que realicen todas las pruebas clínicas , patológicas, de laboratorio, de imágenes e inclusive fotográficas que sean necesarias para la recuperación de la salud como paciente 
y ACEPTO que UNION MEDICA, S.A DE C.V. custodie dichas pruebas así como el historial clínico, AUTORIZO  a UNION MEDICA, S.A DE C.V, para que en caso de ser necesario pueda compartir mis 
resultados personales e historial clínico, previa solicitud y autorización escrita permitida por la ley, o cuando sea exigido por un proceso de tipo legal o cobro de seguro. Esto también 
se aplica a otros miembros de familia y médicos.</p>

<p>c) ACEPTO que la medicina no es una ciencia exacta y he sido debidamente informado de los riesgos y complicaciones específicos que pueden surgir a raíz de estos procedimientos 
mencionados, estos posibles riesgos y/o complicaciones, Médicas, Quirúrgicas, Anestesia, Neurológicas y Otras e inclusive la muerte por lo que  COMPRENDO y soy consciente que 
no existen garantías absolutas de los resultados, pero que los procedimientos están plenamente justificados para la recuperación de la salud como paciente, ya que la necesidad 
de realizarlos supera los riesgos de las posibles complicaciones, en consecuencia EXONERO de todo tipo de responsabilidades, médico-quirúrgico  al doctor de las generales antes expresadas.</p>

<p>d) Asimismo EXONERO DE TODO TIPO DE RESPONSABILIDAD a la Sociedad “ UNION MEDICA, SOCIEDAD ANONIMA DE CAPITAL VARIABLE” que se abrevia “ UNION MEDICA, S.A DE C.V.” y al personal 
del hospital participante en el procedimiento o tratamiento ya detallado, por lo que no realizaré ningún acto legal o reclamo en caso que la evolución después del procedimiento i
ndicado sean distintos a los previstos o se presente alguna complicación inesperada por diversas circunstancias pues me han sido debidamente explicadas.</p>

<p>
e) De igual forma ACEPTO que el presente consentimiento informado no presenta un contrato de adhesión ni imposición de condiciones con la Sociedad UNION MEDICA, S.A DE C.V. 
pues me han brindado la posibilidad de discutir y decidir alternativas que yo he analizado junto con el médico tratante, considerando la condición particular de la enfermedad, 
así como otros factores tanto económicos como personales. Todo esto se me ha explicado en lenguaje comprensible y sin ninguna coacción y ACEPTO lo expresado en este consentimiento 
informado, pues considero que establece las opciones para la recuperación del estado de salud y que los médicos pondrán todos sus conocimientos, esfuerzos y dedicación en la 
realización de dichos procedimientos. También AFIRMO que se me han aclarado todas las dudas al respecto y estoy completamente satisfecho (a) de la información proporcionada, 
así como de la relación costo-beneficio y por lo tanto el estado de necesidad de estos procedimientos.
Por lo que AUTORIZO al Doctor <span class="resaltar"> <?php echo $espacios.$paciente->nombreMedico.$espacios; ?> </span> ; 
para que realice las intervenciones diagnósticas y/o terapéuticas ya referidas.</p>

<p>
Usulután <span class="resaltar"> <?php echo $espacios.$espacios.$espacios.$espacios.date("d").$espacios.$espacios.$espacios.$espacios; ?> </span>(día)
<span class="resaltar"> <?php echo $espacios.$espacios.$espacios.$espacios.date("m").$espacios.$espacios.$espacios.$espacios; ?> </span>(mes)
<span class="resaltar"> <?php echo $espacios.$espacios.$espacios.$espacios.date("Y").$espacios.$espacios.$espacios.$espacios; ?> </span> año.
</p>
         

<div class="pie">
    <div class="pie_detalle">
        
        <div class="pie_izquierda" >
            <table style="width: 90%">
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000;">F. </td>
                </tr>
                <tr>
                    <td colspan="2"> Firma de paciente o Huella según DUI  </td>
                </tr>
            </table>
        </div>

        <div class="pie_derecha">
            <table style="width: 90%">
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000;">F.</td>
                </tr>
                <tr>
                    <td colspan="2"> Firma y Sello de Médico Tratante. </td>
                </tr>
            </table>
        </div>

    </div>
</div>


<p>Nombre_______________________________________________________________</p>
<p>DUI____________________________________________________________________</p>

<p>
(FIRMA DE TESTIGO EN CASO DE QUE EL PACIENTE ESTE INCONSCIENTE O NO LE ACOMPAÑE FAMILIAR O RESPONSABLE)
</p>

<br>
<div class="pie">
    <div class="pie_detalle">
        
        <div class="pie_izquierda" >
            <table style="width: 90%">
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000;">F. </td>
                </tr>
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000; height: 25px"> </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center"> Nombre de Testigo  </td>
                </tr>
            </table>
        </div>

        <div class="pie_derecha">
            <table style="width: 90%">
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000;">F. </td>
                </tr>
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000; height: 25px"> </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center"> Nombre de Testigo </td>
                </tr>
            </table>
        </div>

    </div>
</div>

<br>
<div class="pie">
    <div class="pie_detalle">
        
        <div class="pie_izquierda" >
            <table style="width: 90%">
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000;">DUI</td>
                </tr>
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000; height: 25px">NIT</td>
                </tr>
            </table>
        </div>

        <div class="pie_derecha">
            <table style="width: 90%">
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000;">DUI</td>
                </tr>
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000; height: 25px">NIT</td>
                </tr>
            </table>
        </div>

    </div>
</div>
<br>
<br>

<p>FECHA_________________________________________________________</p>
<p>
f) FIRMA A RUEGO (ESTE ESPACIO SE USARÁ SI EL PACIENTE NO PUEDE
FIRMAR Y COLOCA HUELLA, SEGÚN CONSTE EN EL DUI, A CONTINUACION DEBERA FIRMAR OTRA PERSONA PARA CONSTANCIA).
</p>


<p>FIRMA_____________________________________________________________________</p>
<p>NOMBRE__________________________________________________________________</p>
<p>DUI________________________________________________________________________</p>


<p>g) CONSENTIMIENTO POR SUSTITUCION (Art. 17 “Ley de Deberes y Derechos de Pacientes y prest adores de servicios de salud”)
Este espacio deberá ser llenado por el cónyuge o conviviente o familiares, cuando el paciente esté circunstancialmente incapacitado para hacerlo; b)Cuando el paciente sea niña, niño o adolescente o se trate de un incapacitado legalmente, el derecho corresponde a sus padres, tutor o representante legal.</p>
<br>
<br>
<div class="pie">
    <div class="pie_detalle">
        
        <div class="pie_izquierda" >
            <table style="width: 90%">
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan=" 2" style="text-align: center; height: 25px">Firma</td>
                </tr>
            </table>
        </div>

        <div class="pie_derecha">
            <table style="width: 90%">
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000; text-align: center; font-weight: bold"> <?php echo $responsable->parentescoResponsable; ?> </td>
                </tr>
                <tr>
                    <td colspan=" 2" style="text-align: center; height: 25px">Parentesco</td>
                </tr>
            </table>
        </div>

    </div>
</div>

<br>
<br>
<div class="pie">
    <div class="pie_detalle">
        
        <div class="pie_izquierda" >
            <table style="width: 90%">
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000; text-align: center; font-weight: bold"><?php echo $responsable->nombreResponsable; ?></td>
                </tr>
                <tr>
                    <td colspan=" 2" style="text-align: center; height: 25px">Nombre del Padre/Tutor y/o Representante Legal.</td>
                </tr>
            </table>
        </div>

        <div class="pie_derecha">
            <table style="width: 90%">
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000; text-align: center; font-weight: bold"><?php echo $responsable->duiResponsable; ?></td>
                </tr>
                <tr>
                    <td colspan=" 2" style="text-align: center; height: 25px">Número de DUI</td>
                </tr>
            </table>
        </div>

    </div>
</div>

<br>

<p>h) CLAUSULA DE REVOCACION O NEGACION:  Después de ser informado de la naturaleza y riesgo del padecimiento propuesto, manifiesto de forma libre y consciente
mi NEGACION/REVOCACION DE CONSENTIMIENTO para su realización, haciéndome responsable de las consecuencias que pueden derivarse de esta decisión.</p>

<br>
<br>
<div class="pie">
    <div class="pie_detalle">
        
        <div class="pie_izquierda" >
            <table style="width: 90%">
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td colspan=" 2" style="text-align: center; height: 25px">Firma</td>
                </tr>
            </table>
        </div>

        <div class="pie_derecha">
            <table style="width: 90%">
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td colspan=" 2" style="text-align: center; height: 25px">Parentesco</td>
                </tr>
            </table>
        </div>

    </div>
</div>

<br>
<br>
<div class="pie">
    <div class="pie_detalle">
        
        <div class="pie_izquierda" >
            <table style="width: 90%">
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td colspan=" 2" style="text-align: center; height: 25px">Nombre del Padre/Tutor y/o Representante Legal.</td>
                </tr>
            </table>
        </div>

        <div class="pie_derecha">
            <table style="width: 90%">
                <tr>
                    <td colspan=" 2" style="border-bottom: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td colspan=" 2" style="text-align: center; height: 25px">Número de DUI</td>
                </tr>
            </table>
        </div>

    </div>
</div>
</p>

</div>


