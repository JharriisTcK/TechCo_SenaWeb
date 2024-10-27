

/*
{
    Titulo: "Reparacion",
    Descripcion: "Descripcion de Reparacion",
    BotonTexto: "Ver Mas 1",
    BotonUrl: "VerMas1.php",
}
*/

class SliderBoxNK {

    constructor(NodeObj, UrlAction, dirRaiz, ObjOptionsIn) {
        console.log("new SliderBoxNK() - js")
        var _this=this;
        this.NodeObj=NodeObj||document.createElement("div");
        this.NodeObj.innerHTML="";
        this.NodeObj.className="SliderBoxNK";

        // ------ Values
        this.UrlAction=UrlAction||"No Definido";
        this.dirRaiz=dirRaiz||"";
        
        ObjOptionsIn=ObjOptionsIn||{};
        this.ObjOptions={
            logoSrc: ObjOptionsIn.logoSrc || "",
            valuesIn: ObjOptionsIn.valuesIn || [],
            valuesSend: ObjOptionsIn.valuesSend || []
        };
        this.Values=this.ObjOptions.valuesIn||[];
        this.PagScrollX=0;
        this.PagScrollY=0;
        this.ObjWidth=0;
        this.Timeout=null;
        // ------ Nodos
        this.nodeLista=document.createElement("ul");
        this.nodeLista.className="Lista";
        this.NodeObj.appendChild(this.nodeLista)
        
        this.nodeLogo=document.createElement("img");
        this.nodeLogo.className="Logo";
        if (this.ObjOptions.logoSrc) {
            this.nodeLogo.loading="lazy";
            this.nodeLogo.decoding="async";
            this.nodeLogo.src=this.ObjOptions.logoSrc;
            this.NodeObj.appendChild(this.nodeLogo)
        } 
        // ------ Nodos Next Prev
        var nnPrev=document.createElement("button");
        nnPrev.innerHTML="<"
        nnPrev.className="prev"
        this.NodeObj.appendChild(nnPrev);
        var nnNext=document.createElement("button");
        nnNext.innerHTML=">"
        nnNext.className="next"
        this.NodeObj.appendChild(nnNext);

        this.ConfigureNodes()
        this.jxInfoGet();
        globalThis.addEventListener("resize", function(){_this.ConfigureNodes_Position()}, true)

        nnPrev.addEventListener("click", function() {_this.goPrev()}, true);
        nnNext.addEventListener("click", function() {_this.goNext()}, true);
    }

    ConfigureNodes() {
        this.nodeLista.innerHTML="";
        for (var i=0; i<this.Values.length;i++) {
            this.ConfigureValue_Item(i)
        }
        this.ConfigureNodes_Position();
        this.TimeoutDo();
    }

    ConfigureNodes_Position() {
        var w=this.NodeObj.offsetWidth;
        this.ObjWidth=w;
        // console.log(w)
        var pos=((this.ObjWidth*this.PagScrollX)*-1);
        this.nodeLista.style.left=pos+"px";
        
        for (var i=0; i<this.Values.length;i++) {
            // console.log(w*i)
            var nnLi=this.Values[i].nnLi;
            nnLi.style.left=(w*i)+"px";
        }

    }

    ConfigureValue_Item(i) {
        var Item=this.Values[i];
        var nnLi=document.createElement("li");
        nnLi.className="Item";
        this.nodeLista.appendChild(nnLi);
        
        if(Item.ImgH) {
            var nnImagen=document.createElement("img");
            nnImagen.src=this.dirRaiz+Item.ImgH;
            nnImagen.className="ImagenBG";
            nnLi.appendChild(nnImagen);
        }

        var nnInfo=document.createElement("div");
        nnInfo.className="InfoBox";
        nnLi.appendChild(nnInfo)
        var nnTitulo=document.createElement("div");
        nnTitulo.className="Titulo";
        nnTitulo.innerHTML=Item.Titulo;
        nnInfo.appendChild(nnTitulo)
        var nnDescripcion=document.createElement("div");
        nnDescripcion.className="Descripcion";
        nnDescripcion.innerHTML=Item.Descripcion;
        nnInfo.appendChild(nnDescripcion)
        var nnBoton=document.createElement("a");
        nnBoton.className="Boton";
        nnBoton.innerHTML=Item.BotonTexto;
        nnBoton.href=this.dirRaiz+Item.BotonUrl;
        nnInfo.appendChild(nnBoton);

        this.Values[i].nnLi=nnLi;
    }

    goPrev() {
        // console.log("GoPrev");
        var sl=this.nodeLista.scrollLeft;
        var st=this.nodeLista.scrollTop;
        this.PagScrollX--;
        if(this.PagScrollX<0) {
            this.PagScrollX=this.Values.length-1;
        }
        this.ConfigureNodes_Position();
        this.TimeoutClear();
        this.TimeoutDo();
    }
    
    goNext() {
        // console.log("GoNext");
        var sx=this.nodeLista.scrollLeft;
        var sy=this.nodeLista.scrollTop;
        this.PagScrollX++;
        if(this.PagScrollX>=this.Values.length) {
            this.PagScrollX=0;
        }
        this.ConfigureNodes_Position();
        this.TimeoutClear();
        this.TimeoutDo();
    }

    TimeoutDo() {
        var _this=this;
        this.Timeout=setTimeout(() => {
            _this.goNext();
        }, 10000);
    }

    TimeoutClear() {
        clearTimeout(this.Timeout);
    }

    jxInfoGet() {
        var fd=new FormData();
        fd.append("SliderBoxNK", "InfoGet");
        for(var i=0; i<this.ObjOptions.valuesSend.length; i++) {
            fd.append(
                this.ObjOptions.valuesSend[i][0],
                this.ObjOptions.valuesSend[i][1]
            );
        }
        fetch(this.UrlAction, {body: fd, method: "POST"})
        .then(resp=>resp.text())
        .then(data=>{
            console.group("SliderBoxNK::jxInfoGet()");
            try {
                data=JSON.parse(data);
            } catch (error) {
                console.log(error);
                console.log(data);
                return false;
            }
            console.log(data);
            console.groupEnd();
            if(data.RespuestaBool) {
                this.Values=data.Resultados;
                this.ConfigureNodes();
            } else {
                // console.warn(data);
            }
        })
        .catch(error=>{
            console.warn(error);
        });
    }
}

