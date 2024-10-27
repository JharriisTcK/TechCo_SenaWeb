globalThis.addEventListener('load', function() {
});

globalThis.addEventListener('DOMContentLoaded', function() {
});

class NeoKiriWeb_Footer {
    
    constructor(UrlAction, dirRaiz, ObjOptionsIn) {
        console.log("new MenuTop_Footer() - js")
        var _this=this;

        // ------ Values
        this.urlAction=UrlAction||"No Definido";
        this.dirRaiz=dirRaiz||"";
        
        ObjOptionsIn=ObjOptionsIn||{};
        this.objOptions={
            valuesSend: ObjOptionsIn.valuesSend || [],
            loadingImg: ObjOptionsIn.loadingImg || "img/loading.gif"
        };
        this.UsuarioNombre="";
        this.CarritoProductos=[];
        // ------ NODOS
        this.nodeObj=document.querySelector("footer");
        this.nodeObj.innerHTML="";
        // ------
        this.nodeFooter=document.createElement("div");
        this.nodeFooter.className="InfoFooter";
        this.nodeObj.appendChild(this.nodeFooter);
        // ------
        this.nodeFooterSubmenu=document.createElement("div");
        this.nodeFooterSubmenu.className="InfoFooter_Submenu";
        this.nodeObj.appendChild(this.nodeFooterSubmenu);
        // ------
        this.FooterConfig();
        this.SubmenuConfig();
        this.Cookies_Info();
    }
    
    FooterConfig() {
        var _this=this;
        this.nodeFooter.innerHTML="";

        var nnLogoBox=document.createElement("div");
        this.nodeFooter.appendChild(nnLogoBox);
        var nnLogoImg=document.createElement("img");
        nnLogoImg.encoding="async";
        nnLogoImg.loading="lazy";
        nnLogoImg.src=this.dirRaiz+"logo.png";
        nnLogoImg.className="FooterNeoKiriLogo";
        nnLogoBox.appendChild(nnLogoImg);
        // ------------
        var nnSedesBox=document.createElement("div");
        nnSedesBox.className="SedesBox";
        this.nodeFooter.appendChild(nnSedesBox);
        var nnNeokiriTitulo=document.createElement("div");
        nnNeokiriTitulo.className="FooterTitulo";
        nnNeokiriTitulo.innerHTML="TechCo - Tecnología e Innovación";
        nnSedesBox.appendChild(nnNeokiriTitulo);
        
        var nnMunicipio=document.createElement("div");
        nnMunicipio.innerHTML="Proyecto de Software - Sena 2024";
        nnSedesBox.appendChild(nnMunicipio);
        
        var nnDireccion=document.createElement("div");
        nnDireccion.innerHTML="Analisis y Desarrollo de Software (2758352)";
        nnSedesBox.appendChild(nnDireccion);
        
        var nnTelefono=document.createElement("div");
        nnSedesBox.appendChild(nnTelefono);
        var nnTelefonoA=document.createElement("a");
        nnTelefonoA.href="https://www.jharriistck.dev/";
        nnTelefonoA.innerHTML="Alumno: Harry Jeisson Silva Gonzalez";
        nnTelefono.appendChild(nnTelefonoA);
        // ------------
        var nnRedesBox=document.createElement("div");
        nnRedesBox.className="RedesBox";
        this.nodeFooter.appendChild(nnRedesBox);
        
        var nnChatWhatsappA=document.createElement("a");
        nnChatWhatsappA.href="https://wa.me/573163405789";
        nnChatWhatsappA.target="_BLANK";
        nnRedesBox.appendChild(nnChatWhatsappA);
        var nnChatWhatsappImg=document.createElement("img");
        nnChatWhatsappImg.loading="lazy";
        nnChatWhatsappImg.encoding="async";
        nnChatWhatsappImg.src=this.dirRaiz+"img/WhatsAppButtonGreenSmall.svg";
        nnChatWhatsappImg.alt="Chatear en WhatsApp";
        nnChatWhatsappImg.target="_BLANK";
        nnChatWhatsappA.appendChild(nnChatWhatsappImg);
        
        var nnRedesUl=document.createElement("ul");
        nnRedesBox.appendChild(nnRedesUl);
        
        var nnRedFacebookLi=document.createElement("li");
        nnRedesUl.appendChild(nnRedFacebookLi);
        var nnRedFacebookA=document.createElement("a");
        nnRedFacebookA.href="https://www.facebook.com/";
        nnRedFacebookA.target="_BLANK";
        nnRedFacebookLi.appendChild(nnRedFacebookA);
        var nnRedFacebookImg=document.createElement("img");
        nnRedFacebookImg.loading="lazy";
        nnRedFacebookImg.encoding="async";
        nnRedFacebookImg.src=this.dirRaiz+"img/IconRedFacebook.png";
        nnRedFacebookA.appendChild(nnRedFacebookImg);

        var nnRedInstagramLi=document.createElement("li");
        nnRedesUl.appendChild(nnRedInstagramLi);
        var nnRedInstagramA=document.createElement("a");
        nnRedInstagramA.href="https://www.instagram.com/";
        nnRedInstagramA.target="_BLANK";
        nnRedInstagramLi.appendChild(nnRedInstagramA);
        var nnRedInstagramImg=document.createElement("img");
        nnRedInstagramImg.loading="lazy";
        nnRedInstagramImg.encoding="async";
        nnRedInstagramImg.src=this.dirRaiz+"img/IconRedInstagram.png";
        nnRedInstagramA.appendChild(nnRedInstagramImg);
        
        var nnRedTwitterLi=document.createElement("li");
        nnRedesUl.appendChild(nnRedTwitterLi);
        var nnRedTwitterA=document.createElement("a");
        nnRedTwitterA.href="https://twitter.com/";
        nnRedTwitterA.target="_BLANK";
        nnRedTwitterLi.appendChild(nnRedTwitterA);
        var nnRedTwitterImg=document.createElement("img");
        nnRedTwitterImg.loading="lazy";
        nnRedTwitterImg.encoding="async";
        nnRedTwitterImg.src=this.dirRaiz+"img/IconRedTwitter.png";
        nnRedTwitterA.appendChild(nnRedTwitterImg);
    }

    SubmenuConfig() {
        this.nodeFooterSubmenu.innerHTML="";

        var nnColaboradoresBox=document.createElement("div");
        this.nodeFooterSubmenu.appendChild(nnColaboradoresBox);
        
        var nnP=document.createElement("p");
        nnColaboradoresBox.appendChild(nnP);
        nnP.innerHTML="Bienvenido a nuestro sitio web, donde encontrarás lo que necesitas con la mejor calidad y el mejor servicio. Somos un equipo de profesionales apasionados por lo que hacemos, y estamos comprometidos con brindarte una experiencia única y satisfactoria. Esperamos que disfrutes de tu visita y que vuelvas pronto. ¡Gracias por elegirnos!.";

        var nnSubmenuBox=document.createElement("div");
        this.nodeFooterSubmenu.appendChild(nnSubmenuBox);

        var nnAcerca_Titulo=document.createElement("h2");
        nnSubmenuBox.appendChild(nnAcerca_Titulo);
        nnAcerca_Titulo.innerHTML="- Acerca de -";

        var nnAcerca_Sublista=document.createElement("ul");
        nnSubmenuBox.appendChild(nnAcerca_Sublista);

        var nnNuestraEmpresaLi=document.createElement("li");
        nnAcerca_Sublista.appendChild(nnNuestraEmpresaLi);
        var nnNuestraEmpresaA=document.createElement("a");
        nnNuestraEmpresaA.innerHTML="Nuestra Empresa";
        nnNuestraEmpresaA.href=this.dirRaiz+"Nuestra-Empresa";
        nnNuestraEmpresaLi.appendChild(nnNuestraEmpresaA);
        
        var nnPoliticaDevolucionLi=document.createElement("li");
        nnAcerca_Sublista.appendChild(nnPoliticaDevolucionLi);
        var nnPoliticaDevolucionA=document.createElement("a");
        nnPoliticaDevolucionA.innerHTML="Politica de devolución";
        nnPoliticaDevolucionA.href=this.dirRaiz+"Politica-de-Devolucion";
        nnPoliticaDevolucionLi.appendChild(nnPoliticaDevolucionA);

        var nnPoliticaPrivacidadLi=document.createElement("li");
        nnAcerca_Sublista.appendChild(nnPoliticaPrivacidadLi);
        var nnPoliticaPrivacidadA=document.createElement("a");
        nnPoliticaPrivacidadA.innerHTML="Politica de privacidad";
        nnPoliticaPrivacidadA.href=this.dirRaiz+"Politica-de-Privacidad";
        nnPoliticaPrivacidadLi.appendChild(nnPoliticaPrivacidadA);

        var nnPoliticaCookiesLi=document.createElement("li");
        nnAcerca_Sublista.appendChild(nnPoliticaCookiesLi);
        var nnPoliticaCookiesA=document.createElement("a");
        nnPoliticaCookiesA.innerHTML="Politica de cookies";
        nnPoliticaCookiesA.href=this.dirRaiz+"Politica-de-Cookies";
        nnPoliticaCookiesLi.appendChild(nnPoliticaCookiesA);
    }

    Cookies_Info() {
        let cookies=document.cookie;
        var cookiematch=cookies.match(/CookiesAceptadas=(\d)/);
        console.log(cookiematch);

        if(cookiematch) {
            console.log("Cookies ya aceptadas");
            return true;
        }

        var _this=this;
        let firtstNode=document.body.firstChild;
        let nextNode=firtstNode.nextSibling;

        let timenow = new Date();
        timenow.setDate(timenow.getDate() + 5);
        let timenowUtc=timenow.toUTCString();
    
        let CoockieInfoBox=document.createElement("div");
        CoockieInfoBox.className="Cookie_InfoBox";
        CoockieInfoBox.classList.add("cssnk-gradientanimation");
        document.body.insertBefore(CoockieInfoBox, nextNode);
        
        var Titulo=document.createElement("h1");
        CoockieInfoBox.appendChild(Titulo);
        Titulo.innerHTML="Politica de Cookies";
    
        var p1=document.createElement("p");
        CoockieInfoBox.appendChild(p1);
        p1.innerHTML="Este sitio utiliza cookies propias y de terceros para mejorar la experiencia del usuario";
        
        var p1=document.createElement("p");
        CoockieInfoBox.appendChild(p1);
        p1.innerHTML="Al utilizar nuestro sitio web, aceptas el uso de cookies";
        
        var div=document.createElement("div");
        div.className="Controls";
        CoockieInfoBox.appendChild(div);
        
        var a1=document.createElement("a");
        div.appendChild(a1);
        a1.innerHTML="Politica de Cookies";
        a1.href=this.dirRaiz+"Politica-de-Cookies";
        
        var button1=document.createElement("button");
        div.appendChild(button1);
        button1.innerHTML="Aceptar";
        button1.onclick=function() {
            document.cookie="CookiesAceptadas=1; expires="+timenowUtc;
            document.body.removeChild(CoockieInfoBox);
        }
    }
}