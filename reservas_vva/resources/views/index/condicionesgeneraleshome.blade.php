@extends('layouts.home')

@section('pagename', 'Condiciones generales')

@section('content')

    <div class="container">
        <h1 class="title text-center mt-5 titulo-pagina">Condiciones generales de compra</h1>
        <p style="font-size: 20px;margin-bottom: 22px;">
            1. Introducción
        </p>
        <p style="font-size: 17px;margin-bottom: 22px;">
            El presente documento tiene como finalidad el establecer y regular las normas de uso de la web de reservas online: gestioninstalacion.es en adelante (GESTION INSTALACIÓN) . La utilización de esta página, así como la contratación de los servicios que en ella se pone a disposición del usuario, supondrá la aceptación plena y sin reservas de todas y cada una de las presentes condiciones de uso y compra, además de los correspondientes términos legales. Las presentes condiciones generales de compra se aplican a todas las transacciones comerciales realizadas en nuestra tienda virtual y será obligatorio aceptarlas antes de realizar ninguna compra pedido, por lo que le rogamos las lea atentamente.
        </p>
        <p style="font-size: 20px;margin-bottom: 22px;">
            2. Información General
        </p>
        <p style="font-size: 17px;margin-bottom: 22px;">
            En cumplimiento con el deber de información recogido en artículo 10 de la Ley 34/2002, de 11 de julio, de Servicios de la Sociedad de la Información y del Comercio Electrónico, así como el art. 97 de la Ley General para la Defensa de los Consumidores y Usuarios a continuación se reflejan los siguientes datos: Titular: Taller Empresarial S.L. CIF/NIF:  B56096332 Domicilio Social:  Av. del Gran Capitán, 12, planta 3ª, – CECOworking | 14008 de Córdoba Contacto: E-mail de contacto: alfonso@tallerempresarial.es, Teléfono: +34 957 96 10 41
        </p>
        <p style="font-size: 20px;margin-bottom: 22px;">
            3. Actividad
        </p>
        <p style="font-size: 17px;margin-bottom: 22px;">
            GESTION INSTALACIÓN se dedica a la gestión de reservas de pistas deportivas o piscina.
        </p>
        <p style="font-size: 20px;margin-bottom: 22px;">
            4. Contenidos e información suministrada en el website
        </p>
        <p style="font-size: 17px;margin-bottom: 22px;">
            El idioma utilizado por GESTION INSTALACIÓN en la web será el castellano, sin perjuicio de la utilización de otras lenguas, nacionales o autonómicas. GESTION INSTALACIÓN no se responsabiliza de la no comprensión o entendimiento del idioma de la web por el usuario, ni de sus consecuencias. GESTION INSTALACIÓN se reserva el derecho a modificar la oferta comercial presentada en el website (modificaciones sobre productos, precios, promociones y otras condiciones comerciales y de servicio) en cualquier momento. GESTION INSTALACIÓN hace todos los esfuerzos dentro de sus medios para ofrecer la información contenida en el website de forma veraz y sin errores tipográficos. En el caso que en algún momento se produjera algún error de este tipo, ajeno en todo momento a la voluntad de GESTION INSTALACIÓN, se procedería inmediatamente a su corrección. De existir un error tipográfico en alguno de los precios mostrados y algún cliente hubiera tomado una decisión de compra basada en dicho error, GESTION INSTALACIÓN le comunicará al cliente dicho error y el cliente tendrá derecho a rescindir su compra sin ningún coste por su parte. Los contenidos del sitio web de GESTION INSTALACIÓN podrían, en ocasiones, mostrar información provisional sobre algunos productos. En el caso que la información facilitada no correspondiera a las características del producto el cliente tendrá derecho a rescindir su compra sin ningún coste por su parte.     </p>

        <p style="font-size: 20px;margin-bottom: 22px;">
            5. Sistema de venta
        </p>
        <p style="font-size: 17px;margin-bottom: 22px;">Formalización de la compra </p>
        <p style="font-size: 17px;margin-bottom: 22px;">
            Una vez formalizado el pedido, es decir, con la aceptación de las condiciones generales de compra y la confirmación del proceso de compra, GESTION INSTALACIÓN enviará siempre un email al usuario confirmando los detalles de la compra realizada. El cliente declara conocer la política de privacidad y cookies de GESTION INSTALACIÓN.     </p>

        <p style="font-size: 20px;margin-bottom: 22px;">
            6. Precios
        </p>
        <p style="font-size: 17px;margin-bottom: 22px;">
            Todos los precios de los productos que se indican a través de la página web incluyen el IVA.     </p>

        <p style="font-size: 20px;margin-bottom: 22px;">
            7. Impuestos aplicables
        </p>
        <p style="font-size: 17px;margin-bottom: 22px;">
            Los precios de las productos expuestos en la página web incluyen el Impuesto sobre el Valor Añadido (IVA) que, en su caso, sea procedente aplicar.
        </p>

        <p style="font-size: 20px;margin-bottom: 22px;">
            8. Derechos del comprador y política de devoluciones
        </p>
        <p style="font-size: 17px;margin-bottom: 22px;">Disponibilidad del producto</p>
        <p style="font-size: 17px;margin-bottom: 22px;">
            GESTION INSTALACIÓN informa al usuario de la disponibilidad para la reservas de pistas deportivas o piscina. Para el supuesto en que el producto no esté disponible después de haberse realizado el pedido, el usuario será informado por teléfono o por email de la anulación total o parcial de éste. La anulación parcial del pedido da derecho a la anulación de la totalidad del pedido.
        </p>

        @if (request()->slug_instalacion != "eventos-bodega")
        
        <p style="font-size: 20px;margin-bottom: 22px;">
            9.  Política de devoluciones
        </p>
        <p style="font-size: 17px;margin-bottom: 22px;">
            Al tratarse de la reserva de pistas deportivas o piscina, no es posible la devolución de la reserva de la misma.     </p>
        @endif

        <p style="font-size: 20px;margin-bottom: 22px;">
            @if (request()->slug_instalacion == "eventos-bodega")
            9. Obligaciones del cliente
            @else
            10. Obligaciones del cliente
            @endif        </p>
        <p style="font-size: 17px;margin-bottom: 22px;">
            El cliente de GESTION INSTALACIÓN debe ser mayor de edad y se compromete en todo momento a facilitar información veraz sobre los datos solicitados en los formularios de registro de usuario o de realización del pedido, y a mantenerlos actualizados en todo momento. El cliente se compromete a aceptar todas las disposiciones y condiciones recogidas en las presentes Condiciones Generales de Contratación entendiendo que recogen la mejor voluntad de servicio posible para el tipo de actividad que desarrolla GESTION INSTALACIÓN. El cliente declara ser el titular de los datos bancarios aportados en el proceso de compra o tener autorización legal de su legítimo titular.
        </p>

        <p style="font-size: 20px;margin-bottom: 22px;">
            @if (request()->slug_instalacion == "eventos-bodega")
            10. Legislación aplicable y jurisdicción competente
            @else
            11. Legislación aplicable y jurisdicción competente
            @endif        </p>
        <p style="font-size: 17px;margin-bottom: 22px;">
            Las compraventas realizadas con GESTION INSTALACIÓN se someten a la legislación de España.
        </p>






    </div>

@endsection