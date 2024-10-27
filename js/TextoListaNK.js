class TextoListaNK {
    constructor(nodeIn, urlAction, KeyJX, dirRaiz, objOptionsIn) {
        "use strict";
        var _this = this;
        this.nodeObj=nodeIn||document.createElement("div");
        this.nodeObj.innerHTML="";
        this.nodeObj.className = "TextoListaNK";

        this.dirRaiz=dirRaiz;       
        this.urlAction=urlAction;
        this.KeyJX=KeyJX; 
        
        //----------------
        objOptionsIn = objOptionsIn || {};
        this.objOptions = {
            Titulo: objOptionsIn.Titulo||"Texto Lista NeoKiri",
            valuesSend: objOptionsIn.valuesSend || [],
            loadingImg: objOptionsIn.loadingImg || "img/loading.gif"
        };
        //----------------
        this.Values=[];
        //----------------
        this.nodeHeaderBox=document.createElement("div");
        this.nodeObj.appendChild(this.nodeHeaderBox);
        this.nodeFormBox=document.createElement("div");
        this.nodeObj.appendChild(this.nodeFormBox);
        this.nodeStatusBox=document.createElement("div");
        this.nodeObj.appendChild(this.nodeStatusBox);
        this.nodeListaBox=document.createElement("div");
        this.nodeObj.appendChild(this.nodeListaBox);
        this.nodeEditFormBox=document.createElement("div");
        this.nodeObj.appendChild(this.nodeEditFormBox);
        //----------------
        this.jxTexto_Get();
    }

    ConfigForm() {
        var _this=this;
        this.nodeFormBox.innerHTML="";
        var nnForm=document.createElement("form");
        this.nodeFormBox.appendChild(nnForm);
        var nnTextoLabel=document.createElement("label");
        nnForm.appendChild(nnTextoLabel);
        var nnTextoB=document.createElement("b");
        nnTextoB.innerHTML="Texto: "
        nnTextoLabel.appendChild(nnTextoB);
        var nnTextoInput=document.createElement("Input");
        nnTextoInput.required="required";
        nnTextoLabel.appendChild(nnTextoInput);
        var nnSubmit=document.createElement("Input");
        nnSubmit.type="submit";
        nnSubmit.value="Añadir";
        nnTextoLabel.appendChild(nnSubmit);
        nnForm.addEventListener("submit", function(e) {
            e.preventDefault();
            if (!nnTextoInput.value.length) {
                return false;
            }
            _this.jxTexto_Add(nnTextoInput.value);
        }, false);

    }

    ConfigLista() {
        this.nodeListaBox.innerHTML="";
        var nnLista=document.createElement("ul");
        this.nodeListaBox.appendChild(nnLista);
        for(var i=0; i<this.Values.length;i++) {
            this.ConfigListaItem(nnLista, i);
        }
    }


    ConfigListaItem(nnLista, i) {
        var _this=this;
        var valueItem=this.Values[i];
        var nnLi=document.createElement("li");
        nnLista.appendChild(nnLi);
        var nnNombre=document.createElement("div");
        nnNombre.innerHTML=valueItem.Nombre;
        nnLi.appendChild(nnNombre);
        var nnControles=document.createElement("div");
        nnLi.appendChild(nnControles);
        var nnControl_Editar=document.createElement("button");
        nnControl_Editar.innerHTML="Editar";
        nnControles.appendChild(nnControl_Editar);
        var nnControl_Eliminar=document.createElement("button");
        nnControl_Eliminar.innerHTML="Eliminar";
        nnControles.appendChild(nnControl_Eliminar);
        // -------------------
        nnControl_Editar.addEventListener("click", function(e) {
            _this.ConfigListaItem_EditarConfig(i);
        }, true)
        nnControl_Eliminar.addEventListener("click", function(e) {
            _this.jxTexto_Del(i);
        }, true)
    }


    ConfigListaItem_EditarConfig(i) {
        var valueItem=this.Values[i];
        this.nodeEditFormBox.innerHTML="";
        this.nodeEditFormBox.innerHTML=valueItem.Nombre;

    }

    jxTexto_Get(Texto) {
		"use strict";
        this.nodeStatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
		var fd=new FormData();
		fd.append("TextoListaNK", "Texto_Get");
		fd.append("KeyJX", this.KeyJX);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("TextoListaNK::jxTexto_Add()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                this.Values=data.Resultados;
				this.ConfigForm();
                this.ConfigLista();
			} else {
				this.ConfigForm();
			}
            this.nodeStatusBox.innerHTML="";
		})
		.catch(err=>{
			this.nodeStatusBox.innerHTML="Creo que hay un error en el sistema, por favor informanos:<br/>Error: "+err;
			console.error(err);
		});
	}

    jxTexto_Add(Texto) {
		"use strict";
        this.nodeStatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
		var fd=new FormData();
		fd.append("TextoListaNK", "Texto_Add");
		fd.append("KeyJX", this.KeyJX);
		fd.append("Texto", Texto);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("TextoListaNK::jxTexto_Add()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
				this.ConfigForm();
			} else {
				
			}
            this.nodeStatusBox.innerHTML="";
		})
		.catch(err=>{
			this.nodeStatusBox.innerHTML="Creo que hay un error en el sistema, por favor informanos:<br/>Error: "+err;
			console.error(err);
		});
	}

    jxTexto_Del(i) {
		"use strict";
        this.nodeStatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
		var fd=new FormData();
		fd.append("TextoListaNK", "Texto_Del");
		fd.append("KeyJX", this.KeyJX);
		fd.append("TextoID", this.Values[i].TextoID);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("TextoListaNK::jxTexto_Del()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
				this.ConfigForm();
			} else {
				
			}
            this.nodeStatusBox.innerHTML="";
		})
		.catch(err=>{
			this.nodeStatusBox.innerHTML="jxTexto_Del():: Creo que hay un error en el sistema, por favor informanos:<br/>Error: "+err;
			console.error(err);
		});
	}
}