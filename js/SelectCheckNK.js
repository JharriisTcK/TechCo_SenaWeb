/*


SelectCheckNK = InfoGet
KeyJX = string
[[ValuesSend, Value], ... ]

@returns
Valores = [{
    ID: string,
    Titulo: string,
    Descripcion: string,
    Imagen: string
}...[] ] // Lista de Valores Recibidos

ValoresSeleccionados = [{
    ID: string
}]
-------------------------
SelectCheckNK = InfoGuardar
KeyJX = string
Seleccionados = []
[ [ValuesSend[0], ValuesSend[1]]...[] ]

@returns
RespuestaBool = bool
-------------------------


*/

/*jshint esversion: 6 */
class SelectCheckNK {
    constructor(NodeID, KeyJX, dirRaiz, urlAction, objOptionsIn) {
        var _this=this;
        this.nodeObj=NodeID || document.createElement("div");
        this.nodeObj.innerHTML="";
        this.nodeObj.className="SelectCheckNK";
        //---------------------
        this.KeyJX=KeyJX||"";
        this.dirRaiz=dirRaiz||"";
        this.urlAction=urlAction||"No Definido";
        //---------------------
        objOptionsIn=objOptionsIn||{};
        this.objOptions={
            valuesSend:objOptionsIn.valuesSend||[],
            titulo:objOptionsIn.titulo||"Select Check NK",
            loadingImg: objOptionsIn.loadingImg||this.dirRaiz+"img/loading.gif",
            Valores: objOptionsIn.Valores,
            ValoresSeleccionados: objOptionsIn.ValoresSeleccionados
        };
        //---------------------
        this.nodeHeader=document.createElement("div");
        this.nodeHeader.className="nodeHeader";
        this.nodeObj.appendChild(this.nodeHeader);
        //--
        this.nodeStatus=document.createElement("div");
        this.nodeStatus.className="nodeStatus";
        this.nodeObj.appendChild(this.nodeStatus);
        //--
        this.nodeSeleccionados=document.createElement("ul");
        this.nodeSeleccionados.className="nodeForm";
        this.nodeObj.appendChild(this.nodeSeleccionados);
        //--
        this.nodeHR=document.createElement("hr");
        this.nodeObj.appendChild(this.nodeHR);
        //--
        this.nodeNoSeleccionados=document.createElement("ul");
        this.nodeNoSeleccionados.className="nodeLista";
        this.nodeObj.appendChild(this.nodeNoSeleccionados);
        //--
        this.nodeGuardar=document.createElement("button");
        this.nodeGuardar.className="nodeGuardar";
        this.nodeGuardar.innerHTML="Guardar Cambios";
        this.nodeObj.appendChild(this.nodeGuardar);
        //---------------------
        this.Valores=[];
        this.ValoresSeleccionados=[];
        //---------------------
        this.HeaderConfig();
        //---------------------
        this.nodeHeader.onclick=function() {
            console.clear();
            console.log("SelectCheckNK");
            console.log(_this);
        };
        //---------------------
        this.nodeGuardar.onclick=function() {
            _this.jxInfoGuardar();
        };
        //---------------------
        var valoresin=false;
        var valoresseleccionadosin=false;
        if(this.objOptions.Valores) {
            this.Valores=this.objOptions.Valores;
            valoresin=true;
        }
        if(this.objOptions.ValoresSeleccionados) {
            this.ValoresSeleccionados=this.objOptions.ValoresSeleccionados;
            valoresseleccionadosin=true;
        }
        //---------------------
        if(valoresin==true && valoresseleccionadosin==true) {
            this.ConfigValues();
        } else {
            this.jxInfoGet();
        }
    }


    HeaderConfig() {
        this.nodeHeader.innerHTML="";
        var nnTitulo=document.createElement("h3");
        nnTitulo.innerHTML=this.objOptions.titulo;
        this.nodeHeader.appendChild(nnTitulo);
    }

    ConfigValues() {
        if(!this.Valores.length) {
            console.warn("No hay items SelectCheckNK");
            return false;
        }

        for(var i=0; i<this.Valores.length;i++) {
            this.ConfigurarValor_Nodo(i);
        }

        this.ConfigValores_SeleccionadosIn();
        this.ConfigNodos();
    }

    ConfigurarValor_Nodo(Itera) {
        var _this=this;
        var ItemObj=this.Valores[Itera];
        ItemObj.Seleccionado=false;
        //----------------------
        var nnLi=document.createElement("li");
        ItemObj.nodeLi=nnLi;
        var nnLabel=document.createElement("label");
        nnLi.appendChild(nnLabel);
        var nnButtonCheck=document.createElement("input");
        nnButtonCheck.type="checkbox";
        nnButtonCheck.innerHTML="Eliminar";
        nnLabel.appendChild(nnButtonCheck);
        ItemObj.Checkbox=nnButtonCheck;
        var nnBTitulo=document.createElement("b");
        nnBTitulo.innerHTML=ItemObj.Titulo;
        nnLabel.appendChild(nnBTitulo);
        if(ItemObj.Descripcion) {
            var nnDescripcion=document.createElement("p");
            nnDescripcion.innerHTML=ItemObj.Descripcion;
            nnLabel.appendChild(nnDescripcion);
        }
        nnButtonCheck.onchange=function() {
            console.log("Cambiado: "+this.checked);
            if(this.checked) {
                ItemObj.Seleccionado=true;
            } else {
                ItemObj.Seleccionado=false;
            }
            _this.ConfigNodos();
        };
    }
    
    ConfigValores_SeleccionadosIn() {
        console.info("ConfigValores_SeleccionadosIn");
        for(var i=0; i<this.Valores.length; i++) {
            var Valor=this.Valores[i];
            for(var j=0; j<this.ValoresSeleccionados.length; j++) {
                var ValorSeleccionado=this.ValoresSeleccionados[j];
                if(Valor.ID==ValorSeleccionado) {
                    this.Valores[i].Seleccionado=true;
                    this.Valores[i].Checkbox.checked=true;
                }
            }
        }
    }

    ConfigNodos() {
        this.HeaderConfig();
        if(!this.Valores.length) {
            return false;
        }
        this.nodeSeleccionados.innerHTML="";
        this.nodeNoSeleccionados.innerHTML="";
        for(var i=0; i<this.Valores.length; i++) {
            if(this.Valores[i].Seleccionado==true) {
                this.nodeSeleccionados.appendChild(this.Valores[i].nodeLi);
            } else {
                this.nodeNoSeleccionados.appendChild(this.Valores[i].nodeLi);
            }
        }
    }

    jxInfoGet() {
        this.nodeStatus.innerHTML="";
        var loadingImg=document.createElement("img");
        loadingImg.src=this.objOptions.loadingImg;
        this.nodeStatus.appendChild(loadingImg);
        //-----------
        var fd=new FormData();
        fd.append("SelectCheckNK", "InfoGet");
        fd.append("KeyJX", this.KeyJX);
        for(var i=0; i<this.objOptions.valuesSend.length; i++) {
            fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
        }
        fetch(this.urlAction, {method:"POST", body: fd})
        .then(resp=>resp.json())
        .then(data=>{
            this.nodeStatus.innerHTML="";
            console.group("SelectCheckNK::jxInfoGet() - "+this.objOptions.titulo);
            console.log(data);
            console.groupEnd();
            if(data.RespuestaBool) {
                if(data.Values) {
                    this.Valores=data.Valores;
                }
                if(data.ValuesChecked) {
                    this.ValoresSeleccionados=data.ValoresSeleccionados;
                }
                this.ConfigValues();
            } else {
                this.nodeStatus=data.RespuestaError;
            }
        });
    }

    jxInfoGuardar() {
        console.info("SelectCheckNK.jxInfoGuardar()");
        this.nodeGuardar.disabled=true;
        var buttonText=this.nodeGuardar.value;
        this.nodeGuardar.value="📤​ Guardando Cambios...";
        //-----organizar seleccionados
        var seleccionados=[];
        for(var i=0;i<this.Valores.length;i++) {
            if(this.Valores[i].Seleccionado) {
                seleccionados.push(this.Valores[i].ID);
            }
        }
        //-------------
        var fd=new FormData();
        fd.append("SelectCheckNK", "InfoGuardar");
        fd.append("KeyJX", this.KeyJX);
        fd.append("Seleccionados", seleccionados);
        for(var j=0; j<this.objOptions.valuesSend.length; j++) {
            fd.append(this.objOptions.valuesSend[j][0], this.objOptions.valuesSend[j][1]);
        }
        fetch(this.urlAction, {method:"POST", body: fd})
        .then(resp=>resp.text())
        .then(data=>{
            try {
                data=JSON.parse(data);
            } catch (error) {
                
            }
            console.log(data);
            this.nodeGuardar.value=buttonText;
            this.nodeGuardar.disabled=false;
            if(data.RespuestaBool) {
                // this.jxInfoGet();
            }
        });
    }
}