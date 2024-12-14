/*jshint esversion: 6 */
//var neoKiri;
class FormNK {
	constructor(nodeContenedor, url, key, dirRaiz, objOptionsFormIn) {
		"use strict";
		var _this = this;
		//Configuracion de Clase
		this.urlSend = url;
		this.KeyJX = "";
		if (key) {
			this.KeyJX = key;
		}
		this.dirRaiz=dirRaiz||"";
		this.valuesFormNK = [];
		this.callbackObj = function() {};
		//---Obj Opciones Form
		objOptionsFormIn=objOptionsFormIn||{};
		this.objOptionsForm={
			valuesSend: objOptionsFormIn.valuesSend || [],
			LoadingImg: objOptionsFormIn.LoadingImg || "img/loading.gif"
		}
		//---Nodos de Clase
		this.contenedor = nodeContenedor;
		this.titleDiv = document.createElement("div");
		this.titleDiv.className = "FormNK_FormTitleBox";
		this.contenedor.appendChild(this.titleDiv);
		
		this.form = document.createElement("form");
		this.form.method="POST";
		this.form.enctype="multipart/form-data";
		this.form.className = "FormNK";
		this.form.action = this.urlSend;
		this.contenedor.appendChild(this.form);
		
		this.obj = document.createElement("div");
		this.obj.className = "FormNK_Container";
		this.form.appendChild(this.obj);
		
		this.nodeStatus=document.createElement("div");
		this.nodeStatus.className="StatusBox";
		this.nodeStatus.tabIndex="0";
		this.form.appendChild(this.nodeStatus);
		
		this.nodeFooter=document.createElement("div");
		this.nodeFooter.className="FooterForm";
		this.form.appendChild(this.nodeFooter);

		this.form.addEventListener("submit", function(e) {
			e.preventDefault();
			if(!_this.SubmitVerificar()) {
				return false;
			};
			_this.Submit();
		})
	}
	//-----------------------------------------------------------------------------------
	setTitulo(newTitle, newDescrption, imgSource) {
		"use strict";
		this.titleDiv.innerHTML = "";
		var nnTitle = document.createElement("h2");
		this.titleDiv.appendChild(nnTitle);
		nnTitle.innerHTML = newTitle;
		nnTitle.className = "FormNK_Titulo";
		if(imgSource) {
			var nnImg=document.createElement("img");
			nnImg.src=imgSource;
			this.titleDiv.appendChild(nnImg);
		}
		var nnP = document.createElement("p");
		if (newDescrption) {
			this.titleDiv.appendChild(nnP);
			nnP.innerHTML = newDescrption;
		}
		var nnClear=document.createElement("span");
		nnClear.className="clear";
		this.titleDiv.appendChild(nnClear);
		return [nnTitle, nnP];
	}
	//-------------------------------------------------------------------------------------
	setCallbackSubmit(objCallback) {
		"use strict";
		this.callbackObj = objCallback;
	}
	//-----------------------------------------------------------------------------------
	setKeyJX(newKey) {
		"use strict";
		this.KeyJX = newKey;
	}
	//----------------------------------------------------------------------------------------
	setTexto(text, nombre, valueInp, required, objOptionsIn) {
		"use strict";
		var nnDiv = document.createElement("div");
		nnDiv.className = "FormNK_Texto";
		this.obj.appendChild(nnDiv);
		//--------------
		objOptionsIn = objOptionsIn || {};
		var objOptions = {
			name: nombre,
			type: "texto",
			value: "",
			required: required || false,
			placeholder: objOptionsIn.placeholder || "Ingrese texto",
			descripcion: objOptionsIn.descripcion || "",
			node: nnDiv,
			nodeInput: document.createElement("input")
		};
		this.valuesFormNK.push(objOptions);
		//----------------
		if(!text) {
			nnDiv.innerHTML="Error: Necesito un texto descriptivo para el campo";
			return false;
		}
		if(!objOptions.name) {
			nnDiv.innerHTML="Error: Necesito un nombre de campo";
			return false;
		}
		//-------------
		if(objOptions.descripcion) {
			var nnP=document.createElement("p");
			nnDiv.appendChild(nnP);
			nnP.innerHTML=objOptions.descripcion;
		}
		//-------------
		var nnLabel = document.createElement("label");
		nnDiv.appendChild(nnLabel);
		var nnTexto = document.createElement("b");
		nnTexto.innerHTML = text;
		nnLabel.appendChild(nnTexto);
		let nnInput = objOptions.nodeInput;
		nnInput.name = nombre;
		nnInput.type = "text";
		nnInput.placeholder = objOptions.placeholder;
		if (valueInp) {
			nnInput.setAttribute("value", valueInp);
			objOptions.value=valueInp;
		}
		if (required) {
			nnInput.setAttribute("required", "required");
			nnTexto.innerHTML="❗​ "+nnTexto.innerHTML;
		}
		nnLabel.appendChild(nnInput);
		var nnDivInfo = document.createElement("div");
		nnDiv.appendChild(nnDivInfo);
		objOptions.nodeInput = nnInput;
		objOptions.nodeInfo = nnDivInfo;
		//---------------------------------------------
		nnInput.onchange=function() {
			objOptions.value=nnInput.value;
		};
		nnTexto.onclick = function () {
			console.group("FormNK::setTexto() - " + nombre);
			console.info(objOptions);
			console.groupEnd();
		};
		return objOptions;
	}
	//-------------------------------------------------------------------------------
	setTextoCheck(texto, nombre, valueInp, required, objOptionsIn) {
		"use strict";
		objOptionsIn = objOptionsIn || {};
		var objOptions = {
			name: nombre,
			placeholder: objOptionsIn.placeholder || "Ingrese un Nick/Usuario",
			required: required || false
		};
		this.valuesFormNK.push(objOptions);
		var nnDiv = document.createElement("div");
		this.obj.appendChild(nnDiv);
		var nNickLabel = document.createElement("label");
		nnDiv.appendChild(nNickLabel);
		var nNickTexto = document.createElement("b");
		nNickTexto.innerHTML = texto;
		nNickLabel.appendChild(nNickTexto);
		var nNick = document.createElement("input");
		nNick.type = "text";
		nNick.setAttribute("name", nombre);
		nNick.setAttribute("required", true);
		nNickLabel.appendChild(nNick);
		var nnInfo = document.createElement("div");
		nnDiv.appendChild(nnInfo);
		objOptions.nodeInput = nNick;
		return objOptions;
	}
	//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--
	setNumero(text, nombre, valueInp, required, objOptionsIn) {
		"use strict";
		var nnDiv = document.createElement("div");
		nnDiv.className="FormNK_Numero";
		this.obj.appendChild(nnDiv);
		//-------------
		objOptionsIn = objOptionsIn || {};
		var objOptions = {
			name: nombre,
			type: "numero",
			value: 0,
			required: required || false,
			placeholder: objOptionsIn.placeholder || "Ingrese un numero",
			min: objOptionsIn.min || null,
			max: objOptionsIn.max || null,
			node: nnDiv,
			nodeInput: document.createElement("input")
		};
		this.valuesFormNK.push(objOptions);
		//--------------------------
		if(!text) {
			nnDiv.innerHTML="Necesito un texto descriptivo de campo";
			return false;
		}
		if(!objOptions.name) {
			nnDiv.innerHTML="Necesito un nombre de campo";
			return false;
		}
		//------------------------
		var nnnumLabel = document.createElement("label");
		nnDiv.appendChild(nnnumLabel);
		var nnnumTexto = document.createElement("b");
		nnnumTexto.innerHTML = text;
		nnnumLabel.appendChild(nnnumTexto);
		var nnInput = objOptions.nodeInput;
		nnInput.name = nombre;
		nnInput.type = "number";
		nnInput.placeholder = objOptions.placeholder;
		objOptions.value=valueInp;
		if (valueInp) {
			nnInput.setAttribute("value", valueInp);
		}
		if (required) {
			nnInput.setAttribute("required", "required");
		}
		if (!isNaN(objOptions.min)) {
			nnInput.min = (objOptions.min) ? objOptions.min : 0;
		}
		if (objOptions.max) {
			nnInput.max = objOptions.max;
		}
		nnnumLabel.appendChild(nnInput);
		nnInput.addEventListener("change", function(){
			objOptions.value=nnInput.value;
		}, false);
		return objOptions;
	}
	
	//******************************************************************
	
	setTextarea(text, nombre, valueInp, required, objOptionsIn) {
		"use strict";
		objOptionsIn = objOptionsIn || {};
		var nnDiv = document.createElement("div");
		nnDiv.className="FormNK_Textarea";
		this.obj.appendChild(nnDiv);
		//-----------------------
		objOptionsIn=objOptionsIn||{};
		var objOptions = {
			name: nombre,
			type: "textarea",
			value: valueInp || "",
			required: required || false,
			placeholder: objOptionsIn.placeholder || "Ingrese Texto",
			descripcion: objOptionsIn.descripcion || "",
			limite: objOptionsIn.limite,
			node: nnDiv
		};
		//--------------------------------
		var nnLabel = document.createElement("label");
		nnDiv.appendChild(nnLabel);
		var nnTexto = document.createElement("b");
		nnTexto.innerHTML = text;
		nnTexto.className = "only";
		nnLabel.appendChild(nnTexto);
		var nnBr = document.createElement("br");
		nnLabel.appendChild(nnBr);
		if(objOptions.descripcion) {
			var nnP = document.createElement("p");
			nnP.innerHTML=objOptions.descripcion;
			nnLabel.appendChild(nnP);
		}
		var nnInput = document.createElement("textarea");
		nnInput.setAttribute("name", nombre);
		if(objOptions.limite) {
			nnInput.setAttribute("maxlength", objOptions.limite);
		}
		nnInput.setAttribute("placeholder", objOptions.placeholder);
		if (objOptions.value) {
			nnInput.setAttribute("value", objOptions.value);
			nnInput.innerHTML = objOptions.value;
		}
		if (objOptions.required) {
			nnInput.required = "required";
			nnTexto.innerHTML+=" ❗​";
		}
		nnLabel.appendChild(nnInput);
		//------------------------
		this.valuesFormNK.push(objOptions);
		//---------------------
		nnInput.onchange = function () {
			var txtInp = nnInput.value.replace(/\r?\n/g, "[br]");
			objOptions.value = txtInp;
			nnInput.value = txtInp;
			nnInput.innerHTML = txtInp;
			if(objOptions.limite) {
				var txtlimit=txtInp.substring(0, objOptions.limite);
				objOptions.value = txtlimit;
				nnInput.value = txtlimit 
				nnInput.innerHTML = txtlimit 
			}
		};
		nnTexto.onclick = function () {
			console.group("FormNK::setTextarea() - "+nombre);
			console.info(objOptions);
			console.groupEnd();
		};
	}
	//*********************************************************************
	setEmail(text, nombre, valueInp, required, objOptionsIn) {
		"use strict";
		objOptionsIn = objOptionsIn || {};
		var objOptions = {
			placeholder: objOptionsIn.placeholder || "user@server.com"
		};
		var nnemDiv = document.createElement("DIV");
		this.obj.appendChild(nnemDiv);
		var nnemLabel = document.createElement("LABEL");
		var nnemTexto = document.createElement("B");
		nnemTexto.innerHTML = text;
		nnemLabel.appendChild(nnemTexto);
		var nnemInput = document.createElement("INPUT");
		nnemLabel.appendChild(nnemInput);
		nnemInput.name = nombre;
		nnemInput.type = "email";
		if (valueInp) {
			nnemInput.setAttribute("value", valueInp);
		}
		if (required) {
			nnemInput.required = "required";
		}
		nnemInput.placeholder = objOptions.placeholder;
		var nnemSalto = document.createElement("BR");
		nnemLabel.appendChild(nnemSalto);
		nnemDiv.appendChild(nnemLabel);
	}
	//***********************************************************************
	setRadio(texto, nombre, opcionesArr, objOptionsIn) {
		"use strict";
		console.group("FormNK::setRadio() - " + nombre);
		console.info(opcionesArr);
		var nnDiv = document.createElement("DIV");
		nnDiv.className="FormNK_Radio";
		this.obj.appendChild(nnDiv);
		//-----------------------------
		objOptionsIn = objOptionsIn || {};
		var objOptions = {
			name: nombre,
			type: "radio",
			required: true, //TODO: prerarar el required
			node: nnDiv,
			value: null //TODO: el valor al cambiar
		};
		this.valuesFormNK.push(objOptions);
		//-------------------------------
		var nnTexto = document.createElement("b");
		nnTexto.innerHTML = texto;
		nnTexto.className = "only";
		nnDiv.appendChild(nnTexto);
		for (var i = 0; i < opcionesArr.length; i++) {
			var nnLabel = document.createElement("label");
			var nnRadio = document.createElement("input");
			nnRadio.type = "radio";
			nnRadio.setAttribute("name", nombre);
			nnRadio.setAttribute("value", opcionesArr[i][1]);
			if (opcionesArr[i][2]) {
				nnRadio.setAttribute("checked", "checked");
			}
			var nnSpan = document.createElement("span");
			nnSpan.innerHTML = opcionesArr[i][0];
			nnLabel.appendChild(nnRadio);
			nnLabel.appendChild(nnSpan);
			nnDiv.appendChild(nnLabel);
		}
		console.groupEnd();
	}


	/*{
		Nombre: 
		Imagen: 
		Valor: 
		Seleccionado: 
	}*/
	setCheckbox(texto, nombre, opcionesArrIn, required) {
		"use strict";
		console.group("FormNK::setCheckBox() - "+nombre);
		var nnDiv = document.createElement("div");
		this.obj.appendChild(nnDiv);
		console.log(opcionesArrIn);
		var nnTexto = document.createElement("b");
		nnTexto.innerHTML = texto;
		nnTexto.className = "only";
		nnDiv.appendChild(nnTexto);
		var arr = opcionesArrIn;
		for (var i = 0; i < arr.length; i++) {
			var nnLabel = document.createElement("label");
			nnDiv.appendChild(nnLabel);
			var nnRadio = document.createElement("input");
			nnRadio.type = "checkbox";
			nnRadio.name = nombre + "[]";
			nnRadio.value = arr[i].Valor;
			if (arr[i].Seleccionado) {
				nnRadio.setAttribute("checked", "checked");
			}
			nnLabel.appendChild(nnRadio);
			if(arr[i].Imagen) {
				var nnImg=document.createElement("img");
				nnImg.src=this.dirRaiz + arr[i].Imagen;
				nnImg.title=arr[i].Nombre;
				nnLabel.appendChild(nnImg);
			} else {
				var nnSpan = document.createElement("span");
				nnSpan.innerHTML = arr[i].Nombre;
				nnLabel.appendChild(nnSpan);
			}
		}
		var nnSalto = document.createElement("br");
		nnDiv.appendChild(nnSalto);
		console.groupEnd();
	}
	
	setHidden(nombre, valor, objOptionsIn) {
		"use strict";
		var nnHidden = document.createElement("input");
		nnHidden.type = "hidden";
		nnHidden.name = nombre;
		if (valor) {
			nnHidden.setAttribute("value", valor);
		}
		objOptionsIn=objOptionsIn||{};
		var objOptions={
			name: nombre,
			type: "hidden",
			value: valor||"",
			node: nnHidden,
			nodeInput: nnHidden
		}
		this.obj.appendChild(nnHidden);
		this.valuesFormNK.push(objOptions);
	}
	/* ************************************************************ */
	//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--
	//Crea una funcion para las fechas, el metodo de entrada sera 2020-12-31, la salida un objeto con valores separados
	setFecha(texto, nombre, valorIn, required, addHoraBool, objOptionsIn) {
		var setFechaObj=FormNK.setFecha(texto, nombre, valorIn, required, addHoraBool, objOptionsIn);
		this.valuesFormNK.push(setFechaObj);
		this.obj.appendChild(setFechaObj.nodeDiv);
		return setFechaObj;
	}

	static setFecha(texto, nombre, valorIn, required, addHoraBool, objOptionsIn) {
		"use strict";
		var valor={};
		var fechaActual = new Date();
		if(valorIn) {
			valor = neoKiri.FechaStrToObjNK(valorIn);
		} else {
			valor= {
				Ano: fechaActual.getFullYear(),
				Mes: fechaActual.getMonth() + 1,
				MesDia: fechaActual.getDate(),
				Hora: fechaActual.getHours(),
				Minutos: fechaActual.getMinutes(),
			};
		}
		var nnode = document.createElement("div");
		nnode.className = "FormNK_fecha";
		//-------------------------------
		objOptionsIn = objOptionsIn || {};
		var objOptions = {
			name:nombre,
			type:"fecha",
			value: "",
			node: nnode
		}
		//-------------------------------
		var nnText = document.createElement("b");
		nnText.innerHTML = texto;
		nnode.appendChild(nnText);
		nnode.appendChild(document.createElement("br"));
		//-------------------------------
		var nombreDias = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
		var configObj = {
			name: nombre,
			type: "fecha",
			dia: valor.MesDia, 
			mes: valor.Mes,
			ano: valor.Ano, 
			hora: valor.Hora, 
			min: valor.Minutos, 
			addHora: addHoraBool || false,
			nodeDiv: nnode,
			nodeInput: null,
			value: null
		};
		configObj.configAnoInicio = objOptionsIn.configAnoInicio || configObj.ano - 50;
		configObj.configAnoFinal = objOptionsIn.configAnoFinal || configObj.ano + 50;
		//---------------------
		nnText.onclick = function () {
			console.group("FormNK::setFecha() - " + nombre);
			console.log("In");
			console.log(valorIn);
			console.log("obj");
			console.log(configObj);
			console.groupEnd();
		};
		//-----
		var inpBoxFecha = document.createElement("div");
		inpBoxFecha.className = "inpBox";
		nnode.appendChild(inpBoxFecha);
		//-----
		var nnTextFecha = document.createElement("b");
		inpBoxFecha.appendChild(nnTextFecha);
		nnTextFecha.innerHTML = "Fecha: ";
		//------------------------------
		var nnTextValueInp=document.createElement("input");
		nnTextValueInp.setAttribute("name", nombre+"Value");
		nnTextValueInp.type="hidden";
		inpBoxFecha.appendChild(nnTextValueInp);
		//------------------------------
		var nnTextAnoInp = document.createElement("select");
		configObj.nodeInput=nnTextAnoInp;
		inpBoxFecha.appendChild(nnTextAnoInp);
		nnTextAnoInp.setAttribute("name", nombre + "Ano");
		for (var an = configObj.configAnoInicio; an <= configObj.configAnoFinal; an++) {
			var optAn = document.createElement("option");
			optAn.setAttribute("value", an);
			optAn.innerHTML = an;
			if (an === configObj.ano) {
				optAn.setAttribute("selected", "selected");
			}
			nnTextAnoInp.appendChild(optAn);
		}
		//-----------------------------------------
		var nnTextMesInp = document.createElement("select");
		inpBoxFecha.appendChild(nnTextMesInp);
		nnTextMesInp.setAttribute("name", nombre + "Mes");
		for (var mt = 1; mt <= 12; mt++) {
			var optMes = document.createElement("option");
			var mtv=(mt<10)?"0"+mt:mt;
			optMes.setAttribute("value", mtv);
			optMes.innerHTML = nombreDias[mt];
			if (mt === parseInt(configObj.mes)) {
				optMes.setAttribute("selected", "selected");
			}
			nnTextMesInp.appendChild(optMes);
		}
		//--------------------------------------------------------
		var nnTextDiaInp = document.createElement("select");
		inpBoxFecha.appendChild(nnTextDiaInp);
		nnTextDiaInp.setAttribute("name", nombre + "Dia");
		var setDiasMesInp = function () {
			nnTextDiaInp.innerHTML = "";
			var diasDeMes = FormNK.getDiasDeMes(configObj.ano, configObj.mes);
			if (parseInt(configObj.dia) > diasDeMes) {
				configObj.dia = 1;
			}
			for (var d = 1; d <= diasDeMes; d++) {
				var optDiaMes = document.createElement("OPTION");
				var dv=(d<10)?"0"+d:d;
				optDiaMes.setAttribute("value", dv);
				optDiaMes.innerHTML = d;
				if (d === configObj.dia) {
					optDiaMes.setAttribute("selected", "selected");
				}
				nnTextDiaInp.appendChild(optDiaMes);
			}
		};
		setDiasMesInp();
		//-------------------------------------------------------
		var changeAnoValue = function () {
			configObj.ano = parseInt(nnTextAnoInp.value);
			setDiasMesInp();
			setValueObj();
		};
		var changeMesValue = function () {
			configObj.mes = parseInt(nnTextMesInp.value);
			setDiasMesInp();
			setValueObj();
		};
		var changeDiaValue = function () {
			configObj.dia = parseInt(nnTextDiaInp.value);
			setValueObj();
		};
		nnTextAnoInp.addEventListener("change", changeAnoValue, false);
		nnTextMesInp.addEventListener("change", changeMesValue, false);
		nnTextDiaInp.addEventListener("change", changeDiaValue, false);
		//*********
		var nnTextHoraInp = document.createElement("select");
		var nnTextMinsInp = document.createElement("select");
		if (configObj.addHora) {
			var inpBoxHora = document.createElement("div");
			inpBoxHora.className = "inpBox";
			nnode.appendChild(inpBoxHora);
			//-----
			var nnTextHora = document.createElement("b");
			inpBoxHora.appendChild(nnTextHora);
			nnTextHora.innerHTML = "Hora: ";
			inpBoxHora.appendChild(nnTextHoraInp);
			nnTextHoraInp.setAttribute("name", nombre + "Hora");
			var nnPuntos1 = document.createElement("span");
			nnPuntos1.innerHTML = ":";
			inpBoxHora.appendChild(nnPuntos1);
			var meridianActual = "a.m.";
			for(var h=0; h<24; h++) {
				var h0Txt="";
				var hTxt="";
				if(h<10) {
					h0Txt="0";
					if(h===0) {
						hTxt="/12";
					} 
				} else if (h > 11) {
					if(h===12) {
						hTxt="";
					} else {
						hTxt="/"+(h-12);
					}
					meridianActual = "p.m.";
				}
				configObj.mer = meridianActual;
				var optHora = document.createElement("option");
				optHora.setAttribute("value", h0Txt+h);
				optHora.innerHTML = h+hTxt+" "+meridianActual;
				if (h == configObj.hora) {
					optHora.setAttribute("selected", "selected");
				}
				nnTextHoraInp.appendChild(optHora);
			}
			
			inpBoxHora.appendChild(nnTextMinsInp);
			nnTextMinsInp.setAttribute("name", nombre + "Minuto");
			var nnPuntos2 = document.createElement("span");
			nnPuntos2.innerHTML = " ";
			inpBoxHora.appendChild(nnPuntos2);
			for (var m = 0; m < 60; m++) {
				var mTxt="";
				if(m<10) {
					mTxt="0";
				}
				var optMin = document.createElement("option");
				optMin.setAttribute("value", mTxt+m);
				optMin.innerHTML = m;
				if (m === configObj.min) {
					optMin.setAttribute("selected", "selected");
				}
				nnTextMinsInp.appendChild(optMin);
			}
			
			var changeHoraInp = function () {
				configObj.hora = nnTextHoraInp.value;
				setValueObj();
			};
			var changeMinInp = function () {
				configObj.min = nnTextMinsInp.value;
				setValueObj();
			};

			nnTextHoraInp.addEventListener("change", changeHoraInp, false);
			nnTextMinsInp.addEventListener("change", changeMinInp, false);
		}
		//-------------------------------
		var setValueObj=function() {
			if(addHoraBool) {
				configObj.value=nnTextAnoInp.value+"-"+nnTextMesInp.value+"-"+nnTextDiaInp.value+" "+nnTextHoraInp.value+":"+nnTextMinsInp.value+":00";
				nnTextValueInp.value=configObj.value;
				objOptions.value=configObj.value;
			} else {
				configObj.value=nnTextAnoInp.value+"-"+nnTextMesInp.value+"-"+nnTextDiaInp.value;
				nnTextValueInp.value=configObj.value;
				objOptions.value=configObj.value;
			}
		};
		//-------------------------------
		var nnTextAddHoraInp = document.createElement("INPUT");
		nnTextAddHoraInp.setAttribute("type", "hidden");
		nnTextAddHoraInp.setAttribute("name", nombre + "AddHora");
		nnTextAddHoraInp.setAttribute("value", configObj.addHora);
		nnode.appendChild(nnTextAddHoraInp);
		setValueObj();
		return configObj;
	}
	
	/* [
		{
			Nombre: "",
			Valor: "",
			Seleccionado: true
		}
	] */
	setSelect(texto, nombre, valueInpArr, objOptionsIn) {
		"use strict";
		var _this=this;
		//----------
		var nnDiv = document.createElement("DIV");
		nnDiv.className="FormNK_Select";
		this.obj.appendChild(nnDiv);
		//---------------
		objOptionsIn = objOptionsIn || {};
		var objOptions = {
			name: nombre,
			type: "select",
			value: "",
			valueIn: valueInpArr || [],
			node: nnDiv
		};
		this.valuesFormNK.push(objOptions);
		//----------------
		//------------------
		var nnLabel = document.createElement("label");
		nnDiv.appendChild(nnLabel);
		var nnTexto = document.createElement("b");
		nnTexto.innerHTML = texto;
		nnLabel.appendChild(nnTexto);
		var nnSelect = document.createElement("select");
		nnLabel.appendChild(nnSelect);
		nnSelect.name = nombre;
		//Bucle opciones select array
		if (!objOptions.valueIn.length) {
			return false;
		}
		objOptions.value=objOptions.valueIn[0].Valor;
		for (var i = 0; i < objOptions.valueIn.length; i++) {
			var valueInp = objOptions.valueIn[i];
			var nnSelectOpt = document.createElement("option");
			nnSelectOpt.setAttribute("value", valueInp.Valor);
			nnSelectOpt.innerHTML = valueInp.Nombre;
			if (valueInp.Seleccionado) {
				nnSelectOpt.setAttribute("selected", true);
				objOptions.value=valueInp.Valor;
			}
			nnSelect.appendChild(nnSelectOpt);
		}
		nnSelect.addEventListener("change", function(e) {
			objOptions.value=nnSelect.value;
			console.log("valor cambiado a "+nnSelect.value);
		}, false);
	}

	//-------------------------------------------------------------------------------------
	setDatalist(texto, nombre, valueInpArr) {
		"use strict";
		var _this = this;
		var nnDiv = document.createElement("div");
		this.obj.appendChild(nnDiv);
		var nnLabel = document.createElement("label");
		nnDiv.appendChild(nnLabel);
		var nnTexto = document.createElement("b");
		nnLabel.appendChild(nnTexto);
		nnTexto.innerHTML = texto;
		var nnInput = document.createElement("input");
		nnLabel.appendChild(nnInput);
		var nnInputList = document.createElement("datalist");
		nnLabel.appendChild(nnInputList);
	}

	//----------------------------------------------------------------------------------------
	setPass(text, nombre, objOptionsIn) {
		"use strict";
		var nnDiv = document.createElement("div");
		nnDiv.className = "FormNK_Pass";
		this.obj.appendChild(nnDiv);
		//--------------
		objOptionsIn = objOptionsIn || {};
		var objOptions = {
			name: nombre,
			type: "pass",
			value: "",
			required: objOptionsIn.required || true,
			placeholder: objOptionsIn.placeholder || "Ingrese una contraseña",
			descripcion: objOptionsIn.descripcion || "",
			node: nnDiv,
			nodeInput: document.createElement("input")
		};
		this.valuesFormNK.push(objOptions);
		//----------------
		if(!text) {
			nnDiv.innerHTML="Error: Necesito un texto descriptivo para el campo";
			return false;
		}
		if(!nombre) {
			nnDiv.innerHTML="Error: Necesito un nombre de campo";
			return false;
		}
		//-------------
		if(objOptions.descripcion) {
			var nnP=document.createElement("p");
			nnDiv.appendChild(nnP);
			nnP.innerHTML=objOptions.descripcion;
		}
		//-------------
		var nnLabel = document.createElement("label");
		nnDiv.appendChild(nnLabel);
		var nnTexto = document.createElement("b");
		nnTexto.innerHTML = text;
		nnLabel.appendChild(nnTexto);
		var nnInput = objOptions.nodeInput;
		nnInput.name = nombre;
		nnInput.type = "password";
		nnInput.placeholder = objOptions.placeholder;
		if (objOptions.required) {
			nnInput.setAttribute("required", "required");
			nnTexto.innerHTML="❗​ "+nnTexto.innerHTML;
		}
		nnLabel.appendChild(nnInput);
		var nnDivInfo = document.createElement("div");
		nnDiv.appendChild(nnDivInfo);
		//---------------------------------------------
		objOptions.nodeInput = nnInput;
		objOptions.nodeInfo = nnDivInfo;
		//---------------------------------------------
		nnInput.onchange=function() {
			objOptions.value=nnInput.value;
		};
		nnTexto.onclick = function () {
			console.info("FormNK::setPass() - " + nombre);
			console.log(objOptions);
		};
		return objOptions;
	}
	setNewPass(texto, nombre, objOptionsIn) {
		"use strict";
		var _this = this;
		var nnDiv = document.createElement("DIV");
		this.obj.appendChild(nnDiv);
		//--------------
		objOptionsIn = objOptionsIn || {};
		var objOptions = {
			name: nombre,
			type: "newPass",
			value: "",
			required: objOptionsIn.required || false,
			placeholder: objOptionsIn.placeholder || "Ingrese texto",
			descripcion: objOptionsIn.descripcion || "",
			node: nnDiv,
			nodeInput: document.createElement("input"),
			PassCoinciden: false
		};
		this.valuesFormNK.push(objOptions);
		//----------------
		var nnLabel1 = document.createElement("label");
		nnDiv.appendChild(nnLabel1);
		var nnTexto1 = document.createElement("b");
		nnTexto1.innerHTML = texto;
		nnLabel1.appendChild(nnTexto1);
		var nnPass1 = document.createElement("input");
		nnPass1.type = "password";
		nnPass1.setAttribute("name", objOptions.name + "1");
		nnPass1.setAttribute("required", true);
		nnLabel1.appendChild(nnPass1);
		nnDiv.appendChild(document.createElement("br"));
		var nnLabel2 = document.createElement("label");
		nnDiv.appendChild(nnLabel2);
		var nnTexto2 = document.createElement("b");
		nnTexto2.innerHTML = "Repetir " + texto;
		nnLabel2.appendChild(nnTexto2);
		var nnPass2 = document.createElement("input");
		nnPass2.type = "password";
		nnPass2.setAttribute("name", objOptions.name + "2");
		nnPass2.setAttribute("required", true);
		nnLabel2.appendChild(nnPass2);
		var nnInfo = document.createElement("div");
		nnDiv.appendChild(nnInfo);
		var checkPass2=function(nodePass1, nodePass2) {
			"use strict";
			var valor1 = nodePass1.value;
			var valor2 = nodePass2.value;
			if (valor1 === "") {
				return false;
			}
			if (valor2 === "") {
				return false;
			}
			if (valor2 !== valor1) {
				objOptions.PassCoinciden=false;
				objOptions.value="";
				nnInfo.className = "form_info_error";
				nnInfo.innerHTML = "<b>Error:<b/> Las Contraseñas no coinciden";
				nodePass1.focus();
			} else {
				objOptions.PassCoinciden=true;
				objOptions.value=valor1;
				nnInfo.removeAttribute("class");
				nnInfo.innerHTML = "";
			}
		}
		nnPass1.onchange = function () {
			checkPass2(nnPass1, nnPass2);
		};
		nnPass2.onchange = function () {
			checkPass2(nnPass2, nnPass1);
		};
		return true;
	}
	//**********************************************************************************************************************
	
	//**********************************************************************************************************************

	static checkRegxTextInp(typeTextInp, textValue) {
		console.group("checkRegxTextInp: " + typeTextInp);
		console.info(textValue);
		var resultMatch = null;
		switch (typeTextInp) {
			case "url":
				resultMatch = textValue.match(/^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:\/?#[\]@!\$&'\(\)\*\+,;=.]+$/);
				break;
		}
		console.warn(resultMatch);
		var result = (resultMatch) ? true : false;
		console.groupEnd();
		return [result, resultMatch];
	}
	
	static getDiasDeMes(ano, mes) {
		"use strict";
		ano=parseInt(ano);
		mes=parseInt(mes);
		if (mes === 0) {
			mes = 1;
		}
		var a = new Date(ano, mes, 0).getDate();
		return a;
	}
	static formatSizeUnits(bytes) {
		if (bytes >= 1073741824) {
			bytes = (bytes / 1073741824).toFixed(2) + " GB";
		}
		else if (bytes >= 1048576) {
			bytes = (bytes / 1048576).toFixed(2) + " MB";
		}
		else if (bytes >= 1024) {
			bytes = (bytes / 1024).toFixed(2) + " KB";
		}
		else if (bytes > 1) {
			bytes = bytes + " bytes";
		}
		else if (bytes == 1) {
			bytes = bytes + " byte";
		}
		else {
			bytes = "0 bytes";
		}
		return bytes;
	}
	/* ********************************************************* */
	static boxProgress(nodeBox, fileName, progress, total, jxObjCancel) {
		nodeBox.innerHTML="Subiendo "+fileName+" ["+progress+"/"+total+"]";
	}
	static setLoginBox(text, nombre, nodeIn, objOptionsIn ) {
		"use strict";
		console.group("FormNK::setLoginBox()"+ nombre);
		nodeIn.innerHTML="";
		var nnDiv=document.createElement("div");
		nnDiv.className="FormNK_LoginBox";
		nodeIn.appendChild(nnDiv);
		//--------------------------------
		objOptionsIn = objOptionsIn || {};
		var objOptions= {
			actionSend : objOptionsIn.actionSend || null,
			logoSrc: objOptionsIn.logoSrc || "",
			logoSrcTitle: objOptionsIn.logoSrcTitle || "",
			placeholder: "Ingrese su Nick",
			node: nodeIn,
			fullscreen: false,
			centerForm: false
		};

		//-------------------------
		var nnForm=document.createElement("form");
		nnForm.method="POST";
		nnForm.action=objOptions.actionSend;
		nnDiv.appendChild(nnForm);
		//---------------
		var nnDivBlock=document.createElement("div");
		nnDivBlock.className="divBlock";
		nnForm.appendChild(nnDivBlock);
		var nnLogo=document.createElement("img");
		nnLogo.src=objOptions.logoSrc;
		nnForm.appendChild(nnLogo);
		var nnTit=document.createElement("h1");
		nnTit.innerHTML=text || "";
		nnForm.appendChild(nnTit);
		//------------------
		var nnBUser=document.createElement("b");
		nnBUser.innerHTML="Nick/User: ";
		nnForm.appendChild(nnBUser);
		var nnUser=document.createElement("input");
		nnUser.name=nombre+"UserNK";
		nnUser.required=true;
		nnForm.appendChild(nnUser);
		var nnBPass=document.createElement("b");
		nnBPass.innerHTML="Password: ";
		nnForm.appendChild(nnBPass);
		var nnPass=document.createElement("input");
		nnPass.type="password";
		nnPass.name=nombre+"PassNK";
		nnPass.required=true;
		nnForm.appendChild(nnPass);
		//----------------------
		var nnLog=document.createElement("div");
		nnForm.appendChild(nnLog);
		//--------------------
		var nnHidden=document.createElement("input");
		nnHidden.type="hidden";
		nnHidden.name="LoginBoxNK";
		nnHidden.setAttribute("value", nombre);
		nnForm.appendChild(nnHidden);
		//-----------------------
		var nnSubmit=document.createElement("input");
		nnSubmit.type="submit";
		nnSubmit.value="Iniciar Sesion";
		nnForm.appendChild(nnSubmit);
		//-------------------------
		nnTit.onclick=function() {
			console.log(objOptions);
		};
		nnForm.onsubmit=function(e) {
			e.preventDefault();
			nnLog.innerHTML="";
			nnLog.className="";
			var user=nnUser.value;
			var pass=nnPass.value;
			var fd=new FormData();
			fd.append(nombre+"UserNK", user);
			fd.append(nombre+"PassNK", pass);
			fd.append("LoginBoxNK", nombre);
			var jx=new XMLHttpRequest();
			jx.onreadystatechange=function() {
				if(jx.status===200 && jx.readyState===4) {
					var response=jx.responseText;
					console.log(response);
					var respObj=JSON.parse(response);
					if(respObj[0]) {
						nnLog.className="ok";
						nnLog.innerHTML=respObj[1].text;
						globalThis.location=respObj[1].locationDir;
					} else {
						nnLog.className="error";
						nnLog.innerHTML=respObj[1].text;
					}
				}
			};
			jx.open("POST", objOptions.actionSend, true);
			jx.send(fd);
		};
		console.groupEnd();
	}

	static BoxDivComplete(objOptionsIn, _callbackFunc) {
		objOptionsIn=objOptionsIn||{};
		var objOptions={
			ColorBackgroundShadow: objOptionsIn.ColorBackgroundShadow || "rgba(0,0,0,0.5)",
			ColorBackground: objOptionsIn.ColorBackground || "rgb(255,255,255)",
			wW: 0,
			wH: 0,
			pX: 0,
			pY: 0,
			wPercent: objOptionsIn.wPercent || 70,
			hPercent: objOptionsIn.hPercent || 90,
			Titulo: objOptionsIn.Titulo||""
		};
		var dBody=document.querySelector("body");
		var dBodyFirstNode=dBody.firstChild;
		var nnBoxShadow=document.createElement("div");
		var nnBox=document.createElement("div");
		if(objOptions.Titulo) {
			var nnTitulo=document.createElement("h1");
			nnTitulo.innerHTML=objOptions.Titulo;
			nnBox.appendChild(nnTitulo);
		}
		var nnBoxContenido=document.createElement("div");
		nnBox.appendChild(nnBoxContenido);
		dBody.insertBefore(nnBoxShadow, dBodyFirstNode);
		dBody.insertBefore(nnBox, dBodyFirstNode);
		//-------------------------------------------
		dBody.style.overflow="hidden";
		nnBox.style.position="absolute";
		nnBox.style.backgroundColor=objOptions.ColorBackground;
		nnBox.style.zIndex=101;
		nnBox.style.overflow="auto";
		nnBoxShadow.style.position="absolute";
		nnBoxShadow.style.backgroundColor=objOptions.ColorBackgroundShadow;
		nnBoxShadow.style.zIndex=100;
		//-------------------------------------------
		var getSize=function() {
			objOptions.wW=globalThis.innerWidth;
			objOptions.wH=globalThis.innerHeight;
			objOptions.px=globalThis.pageXOffset;
			objOptions.pY=globalThis.pageYOffset;
			//---------

		};
		var resizeBox=function() {
			getSize();
			nnBoxShadow.style.top=objOptions.pY+"px";
			nnBoxShadow.style.left=objOptions.pX+"px";
			nnBoxShadow.style.width=objOptions.wW+"px";
			nnBoxShadow.style.height=objOptions.wH+"px";
			//----------------------------------------
			var wBox=(objOptions.wW*objOptions.wPercent/100);
			var hBox=(objOptions.wH*objOptions.hPercent/100);
			var xBox=((objOptions.wW-wBox)/2);
			var yBox=((objOptions.wH-hBox)/2);
			nnBox.style.top=objOptions.pY+yBox+"px";
			nnBox.style.left=objOptions.pX+xBox+"px";
			nnBox.style.width=wBox+"px";
			nnBox.style.height=hBox+"px";
		};
		//-------------------------------------------
		var cancelBox=function() {
			globalThis.removeEventListener("resize", resizeBox, true);
			dBody.style.overflow="auto";
			dBody.removeChild(nnBox);
			dBody.removeChild(nnBoxShadow);
			if(_callbackFunc) {
				_callbackFunc();
			}
		};
		nnBoxShadow.onclick=cancelBox;
		globalThis.addEventListener("resize", resizeBox, true);
		getSize();
		resizeBox();
		return [nnBoxContenido, nnBoxShadow];
	}

	SubmitVerificar() {
		for(var i=0; i<this.valuesFormNK.length; i++) {
			switch (this.valuesFormNK[i].type) {
				case "texto":
					
				break;

				case "numero":
					
				break;

				case "textarea":
					
				break;

				case "hidden":
					
				break;

				case "fecha":
					
				break;

				case "select":
					
				break;

				case "pass":
					
				break;

				case "newPass":
					if(!this.valuesFormNK[i].PassCoinciden) {
						this.nodeStatus.innerHTML="No se puede enviar: Las Contraseñas no coinciden";
						this.valuesFormNK[i].nodeInput.focus();
						return false;
					}
				break;
			}
		}
		return true;
	}

	Submit() {
		this.nodeStatus.innerHTML="";
		var nnImgLoading=document.createElement("img");
		nnImgLoading.loading="lazy";
		nnImgLoading.encoding="async";
		nnImgLoading.src=this.dirRaiz+this.objOptionsForm.LoadingImg;
		this.nodeStatus.appendChild(nnImgLoading);
		// ----------------
		var fd=new FormData();
		fd.append("FormNK","FormNK_Submit");
		fd.append("KeyJX", this.KeyJX);
		for(var i=0; i<this.valuesFormNK.length; i++) {
			switch (this.valuesFormNK[i].type) {
				case "texto":
					fd.append(this.valuesFormNK[i].name, this.valuesFormNK[i].value);
				break;

				case "numero":
					fd.append(this.valuesFormNK[i].name, this.valuesFormNK[i].value);
				break;

				case "textarea":
					fd.append(this.valuesFormNK[i].name, this.valuesFormNK[i].value);
				break;

				case "hidden":
					fd.append(this.valuesFormNK[i].name, this.valuesFormNK[i].value);
				break;

				case "fecha":
					fd.append(this.valuesFormNK[i].name, this.valuesFormNK[i].value);
				break;

				case "select":
					fd.append(this.valuesFormNK[i].name, this.valuesFormNK[i].value);
				break;

				case "pass":
					fd.append(this.valuesFormNK[i].name, this.valuesFormNK[i].value);
				break;

				case "newPass":
					fd.append(this.valuesFormNK[i].name, this.valuesFormNK[i].value);
				break;
			}
		}
		for (var i = 0; i < this.objOptionsForm.valuesSend.length; i++) {
			fd.append(this.objOptionsForm.valuesSend[i][0], this.objOptionsForm.valuesSend[i][1]);
		}
		fetch(this.urlSend, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
			console.info("FormNK.Submit()");
			try {
				data=JSON.parse(data);
			} catch (error) {
				console.group("FormNK::SubmitError")
				console.log(error);
				console.log(data);
				console.groupEnd()
				return false;
			}
			console.log(data);
			this.nodeStatus.innerHTML="";
			if(data.RespuestaBool) {
				this.nodeStatus.innerHTML="Datos Guardados";
			} else {
				this.nodeStatus.innerHTML=data.RespuestaError;
			}
			this.nodeStatus.focus();
			this.callbackObj();
		})
		.catch(err=>{
			var err="Creo que hay un error en el sistema, por favor informanos:<br/>Error: "+err;
			// this.nodeInfo.innerHTML=err;
			console.error(err);
		});
	}

	finalizar(texto) {
		"use strict";
		var _this = this;
		var nnDiv = document.createElement("div");
		this.nodeFooter.appendChild(nnDiv);
		nnDiv.className = "FormNK_footer";
		var FormNKInputHidden = document.createElement("input");
		FormNKInputHidden.setAttribute("name", "FormNK");
		FormNKInputHidden.setAttribute("type", "hidden");
		FormNKInputHidden.setAttribute("value", "FormNK_Submit");
		nnDiv.appendChild(FormNKInputHidden);
		var keyJX = document.createElement("input");
		keyJX.setAttribute("name", "KeyJX");
		keyJX.setAttribute("type", "hidden");
		keyJX.setAttribute("value", this.KeyJX);
		nnDiv.appendChild(keyJX);
		var nnSubmit = document.createElement("input");
		nnSubmit.type = "submit";
		nnSubmit.value = texto;
		nnDiv.appendChild(nnSubmit);
		var nnSeparator = document.createElement("span");
		nnSeparator.innerHTML = "|";
		nnDiv.appendChild(nnSeparator);
		var nnLimpiar = document.createElement("input");
		nnLimpiar.type = "reset";
		nnLimpiar.value = "Reset Formulario";
		nnDiv.appendChild(nnLimpiar);
		nnSeparator.onclick = function () {
			console.clear();
			console.group("FormNK: Log");
			console.log("FormNK");
			console.log(_this);
			console.log("Values FormNK");
			console.log(_this.valuesFormNK);
			console.groupEnd();
		};
	}

}


















