/* jshint esversion: 6 */
class neoKiri {
	constructor() {
		"use strict";
	}
	static BoxDivComplete(objOptionsIn, _callbackFunc) {
		objOptionsIn = objOptionsIn || {};
		var objOptions = {
			ColorBackgroundShadow: objOptionsIn.ColorBackgroundShadow || "rgba(0,0,0,0.5)",
			ColorBackground: objOptionsIn.ColorBackground || "rgb(255,255,255)",
			wW: 0,
			wH: 0,
			pX: 0,
			pY: 0,
			wPercent: objOptionsIn.wPercent || 70,
			hPercent: objOptionsIn.hPercent || 90,
			Titulo: objOptionsIn.Titulo || ""
		};
		var dBody = document.querySelector("body");
		var dBodyFirstNode = dBody.firstChild;
		var nnBoxShadow = document.createElement("div");
		var nnBox = document.createElement("div");
		if (objOptions.Titulo) {
			var nnTitulo = document.createElement("h1");
			nnTitulo.innerHTML = objOptions.Titulo;
			nnBox.appendChild(nnTitulo);
		}
		var nnBoxContenido = document.createElement("div");
		nnBox.appendChild(nnBoxContenido);
		dBody.insertBefore(nnBoxShadow, dBodyFirstNode);
		dBody.insertBefore(nnBox, dBodyFirstNode);
		//-------------------------------------------
		dBody.style.overflow = "hidden";
		nnBox.style.position = "absolute";
		nnBox.style.backgroundColor = objOptions.ColorBackground;
		nnBox.style.zIndex = 101;
		nnBox.style.overflow = "auto";
		nnBoxShadow.style.position = "absolute";
		nnBoxShadow.style.backgroundColor = objOptions.ColorBackgroundShadow;
		nnBoxShadow.style.zIndex = 100;
		//-------------------------------------------
		var getSize = function () {
			objOptions.wW = globalThis.innerWidth;
			objOptions.wH = globalThis.innerHeight;
			objOptions.px = globalThis.pageXOffset;
			objOptions.pY = globalThis.pageYOffset;
			//---------

		};
		var resizeBox = function () {
			getSize();
			nnBoxShadow.style.top = objOptions.pY + "px";
			nnBoxShadow.style.left = objOptions.pX + "px";
			nnBoxShadow.style.width = objOptions.wW + "px";
			nnBoxShadow.style.height = objOptions.wH + "px";
			//----------------------------------------
			var wBox = (objOptions.wW * objOptions.wPercent / 100);
			var hBox = (objOptions.wH * objOptions.hPercent / 100);
			var xBox = ((objOptions.wW - wBox) / 2);
			var yBox = ((objOptions.wH - hBox) / 2);
			nnBox.style.top = objOptions.pY + yBox + "px";
			nnBox.style.left = objOptions.pX + xBox + "px";
			nnBox.style.width = wBox + "px";
			nnBox.style.height = hBox + "px";
		};
		//-------------------------------------------
		var cancelBox = function () {
			globalThis.removeEventListener("resize", resizeBox, true);
			dBody.style.overflow = "auto";
			dBody.removeChild(nnBox);
			dBody.removeChild(nnBoxShadow);
			if (_callbackFunc) {
				_callbackFunc();
			}
		};
		nnBoxShadow.onclick = cancelBox;
		globalThis.addEventListener("resize", resizeBox, true);
		getSize();
		resizeBox();
		return [nnBoxContenido, nnBoxShadow];
	}

	static ImgMaximize(ImgUrl, Caption, ObjOptionsIN) {
		//obtener tamaño de la ventana
		var optionsMaximize = {
			wW: globalThis.innerWidth,
			wH: globalThis.innerHeight,
		};
		//Ocultar los scroll del body html
		document.body.style.width = optionsMaximize.wW + "px";
		document.body.style.height = optionsMaximize.wH + "px";
		document.body.style.overflow = "hidden";
		//Obtener primer nodo y crear nodos maximize
		var firstChild = document.body.firstChild;
		var boxMaximize = document.createElement("div");
		boxMaximize.className = "NK_ImgMaximize_Box";
		document.body.insertBefore(boxMaximize, firstChild);
		//----
		var nodeMaximizePicture = new Image();
		nodeMaximizePicture.className = "NK_ImgMaimize_Image";
		boxMaximize.appendChild(nodeMaximizePicture);
		//---
		var nodeMaximizeClose = document.createElement("div");
		nodeMaximizeClose.innerHTML = "❌​";
		nodeMaximizeClose.className = "NK_ImgMaximize_Close";
		boxMaximize.appendChild(nodeMaximizeClose);
		//----
		var nodeMaximizePictureCaption = document.createElement("p");
		nodeMaximizePictureCaption.className = "NK_ImgMaximize_Caption";
		boxMaximize.appendChild(nodeMaximizePictureCaption);
		//----
		var setPreview = function () {
			// boxMaximize.removeChild(nodeMaximizePicture);
			boxMaximize.style.position = "absolute";
			boxMaximize.style.width = optionsMaximize.wW + "px";
			boxMaximize.style.height = optionsMaximize.wH + "px";
			boxMaximize.style.top = (globalThis.scrollY) + "px";
			boxMaximize.style.left = (globalThis.scrollX) + "px";
			boxMaximize.style.zIndex = 1000;
			boxMaximize.style.overflow = "hidden";
			optionsMaximize.wW = globalThis.innerWidth;
			optionsMaximize.wH = globalThis.innerHeight;
			nodeMaximizePicture.src = ImgUrl;
			nodeMaximizePicture.onload = ConfigPicture;
			globalThis.addEventListener("resize", ConfigPicture, false);
			globalThis.addEventListener("scroll", ConfigPicture, false);
			nodeMaximizePictureCaption.innerHTML = Caption;
			// boxMaximize.appendChild(nodeMaximizePicture);
		};

		var ConfigPicture = function () {
			console.group("adjustPicturePrev");
			boxMaximize.style.display = "none";
			optionsMaximize.wW = globalThis.innerWidth;
			optionsMaximize.wH = globalThis.innerHeight;
			boxMaximize.style.display = "block";
			//-----------
			boxMaximize.style.width = optionsMaximize.wW + "px";
			boxMaximize.style.height = optionsMaximize.wH + "px";
			boxMaximize.style.top = (globalThis.scrollY) + "px";
			boxMaximize.style.left = (globalThis.scrollX) + "px";
			//-----------
			nodeMaximizePicture.removeAttribute("style");
			nodeMaximizePicture.style.position = "absolute";
			//optionsMaximize.imgMaximize.style.position="relative";
			var wImg = nodeMaximizePicture.width;
			var hImg = nodeMaximizePicture.height;
			var orImg = null;
			if (wImg >= hImg) {
				orImg = "horizontal";
			} else if (hImg > wImg) {
				orImg = "vertical";
			} else {
				return false;
			}

			console.log("IMG=" + wImg + ":" + hImg + "(" + orImg + ")");
			//-----------	widthBox	---------
			var wBox = boxMaximize.offsetWidth;
			var hBox = boxMaximize.offsetHeight;

			var orBox = null;
			if (wBox >= hBox) {
				orBox = "horizontal";
			} else if (hBox > wBox) {
				orBox = "vertical";
			} else {
				return false;
			}

			console.log("BOX=" + wBox + ":" + hBox + "(" + orBox + ")");
			//----------------------------------------------------
			console.group("Case1 Width");
			var case1P = (wBox * 100) / wImg;
			console.log(case1P + "%");
			var case1W = (wImg * case1P) / 100;
			var case1H = (hImg * case1P) / 100;
			if (case1W > wImg) {
				case1W = wImg;
			}
			if (case1H > hImg) {
				case1H = hImg;
			}
			console.log(case1W + " : " + case1H);
			console.groupEnd();
			//--//
			console.group("Case2 Height");
			var case2P = (hBox * 100) / hImg;
			console.log(case2P + "%");
			var case2W = (wImg * case2P) / 100;
			var case2H = (hImg * case2P) / 100;
			if (case2W > wImg) {
				case2W = wImg;
			}
			if (case2H > hImg) {
				case2H = hImg;
			}
			console.log(case2W + " : " + case2H);
			console.groupEnd();
			//-----------------------------------
			switch (orBox) {
				case "horizontal":
					console.info("Box Horizontal");
					if (wBox < case2W) {
						nodeMaximizePicture.style.left = (wBox / 2) - (case1W / 2) + "px";
						nodeMaximizePicture.style.top = (hBox / 2) - (case1H / 2) + "px";
						nodeMaximizePicture.style.width = case1W + "px";
						nodeMaximizePicture.style.height = case1H + "px";
					} else {
						nodeMaximizePicture.style.left = (wBox / 2) - (case2W / 2) + "px";
						nodeMaximizePicture.style.top = (hBox / 2) - (case2H / 2) + "px";
						nodeMaximizePicture.style.width = case2W + "px";
						nodeMaximizePicture.style.height = case2H + "px";
					}
					break;
				case "vertical":
					console.info("Box Vertical");
					if (hBox < case1H) {
						nodeMaximizePicture.style.top = (hBox / 2) - (case2H / 2) + "px";
						nodeMaximizePicture.style.left = (wBox / 2) - (case2W / 2) + "px";
						nodeMaximizePicture.style.width = case2W + "px";
						nodeMaximizePicture.style.height = case2H + "px";
					} else {
						nodeMaximizePicture.style.top = (hBox / 2) - (case1H / 2) + "px";
						nodeMaximizePicture.style.left = (wBox / 2) - (case1W / 2) + "px";
						nodeMaximizePicture.style.width = case1W + "px";
						nodeMaximizePicture.style.height = case1H + "px";
					}
					break;
				default:
					console.warn("QUe paho");
					return false;
			} //fin switch orBox
			console.groupEnd();
		};

		var closeMaximizePreview = function () {
			document.body.removeChild(boxMaximize);
			document.body.style.width = "auto";
			document.body.style.height = "auto";
			document.body.style.overflow = "auto";
			document.body.removeAttribute("style");
			globalThis.removeEventListener("resize", ConfigPicture, false);
			globalThis.removeEventListener("scroll", ConfigPicture, false);
		};
		//---------------------------
		nodeMaximizeClose.onclick = closeMaximizePreview;
		setPreview();
	}

	static FechaStrToObjNK(valor, objOptionsIn) {
		"use strict";
		objOptionsIn = objOptionsIn | {};
		var objOptions = {
			byConsole: objOptionsIn.byConsole | false
		};
		if (objOptions.byConsole) {
			console.group("neoKiri::FechaStrToObjNK / " + valor);
		}
		if (!valor) {
			return false;
		}
		var arrayDateTime = valor.split(" ");
		var arrayFecha = arrayDateTime[0].split("-");
		arrayFecha[0] = Number(arrayFecha[0]);
		arrayFecha[1] = Number(arrayFecha[1]) - 1;
		arrayFecha[2] = Number(arrayFecha[2]);

		var arrayHora = ["00", "00", "00"];
		if (arrayDateTime[1]) {
			arrayHora = arrayDateTime[1].split(":");
			arrayHora[0] = Number(arrayHora[0]);
			arrayHora[1] = Number(arrayHora[1]);
			arrayHora[2] = Number(arrayHora[2]);
		}

		var fechaObj = new Date(arrayFecha[0], arrayFecha[1], arrayFecha[2], arrayHora[0], arrayHora[1], arrayHora[2]);

		var dias = [
			"Domingo",
			"Lunes",
			"Martes",
			"Miercoles",
			"Jueves",
			"Viernes",
			"Sabado"
		];
		var meses = [
			"Enero",
			"Febrero",
			"Marzo",
			"Abril",
			"Mayo",
			"Junio",
			"Julio",
			"Agosto",
			"Septiembre",
			"Octubre",
			"Noviembre",
			"Diciembre"
		];

		var Ano = fechaObj.getFullYear();
		var Mes = fechaObj.getMonth();
		var MesNombre = meses[Mes];
		var MesDia = fechaObj.getDate();
		var Dia = fechaObj.getDay();
		var DiaNombre = dias[Dia];

		var Hora = fechaObj.getHours();
		var HoraPM = Hora;
		var HoraPMTxt = Hora;
		var Meridian = "a.m.";
		if (Hora > 12) {
			HoraPM = Hora - 12;
			Meridian = "p.m.";
		}
		if (Hora == 12) {
			HoraPM = Hora;
			Meridian = "p.m.";
		}
		if (!HoraPM) {
			HoraPM = 12;
		}
		if (HoraPM < 10) {
			HoraPMTxt = "0" + HoraPM;
		} else {
			HoraPMTxt = HoraPM;
		}
		var Minutos = fechaObj.getMinutes();
		var MinutosTxt = Minutos;
		if (Minutos < 10) {
			MinutosTxt = "0" + Minutos;
		}
		var Segundos = fechaObj.getSeconds();

		var obj = {
			DateObj: fechaObj,
			Datetime: fechaObj.getTime(),
			Ano: Ano,
			Mes: Mes + 1,
			MesNombre: MesNombre,
			MesDia: MesDia,
			Dia: Dia,
			DiaNombre: DiaNombre,
			DateNombre: DiaNombre + ", " + MesDia + " de " + MesNombre + " de " + Ano,
			Hora: Hora,
			HoraPM: HoraPM,
			HoraPMTxt: HoraPMTxt,
			Minutos: Minutos,
			MinutosTxt: MinutosTxt,
			Segundos: Segundos,
			Meridian: Meridian,
			HoraNombre: HoraPMTxt + ":" + MinutosTxt + " " + Meridian
		};
		if (objOptions.byConsole) {
			console.log(obj);
			console.log(objOptions);
			console.groupEnd();
		}
		return obj;
	}

	static FechasDiferenciaNow(Fecha1IN, duracionMins, objOptionsIn) {
		//--------------------------
		objOptionsIn = objOptionsIn | {};
		var objOptions = {
			byConsole: false
		};
		if (objOptions.byConsole) {
			console.group("neoKiri::FechasDiferenciaNow / " + Fecha1IN + " : " + duracionMins);
		}
		//--------------------------
		var fecha1 = neoKiri.FechaStrToObjNK(Fecha1IN);
		var fecha1time = fecha1.Datetime;
		//--------------------------
		duracionMins = (duracionMins) ? Number(duracionMins) : 60;
		var duracionms = duracionMins * 60 * 1000;
		var duraciontime = fecha1time + duracionms;
		//--------------------------
		var fechaActual = new Date();
		var fechaActualTime = fechaActual.getTime();
		//-------------------Configurar el Estado con el tiempo actual
		var estadofecha = "";
		var estadofechatime = 0;
		var estadofechatimetranscurrido = 0;
		if (fechaActualTime < fecha1time) {
			estadofecha = "Pendiente";
			estadofechatime = fecha1time - fechaActualTime;
		} else { // De lo contrario si el tiempo actual ya paso al inicio del evento, puede estar en proceso o ya termino otra comprobacion
			if (fechaActualTime < duraciontime) {
				estadofecha = "Proceso";
				estadofechatime = duraciontime - fechaActualTime; //finaliza en
				estadofechatimetranscurrido = fechaActualTime - fecha1time; //Han transcurrido
			} else {
				estadofecha = "Termino";
				estadofechatime = fechaActualTime - duraciontime;
			}
		}
		//-------------------Configurar el Estado con el tiempo actual
		var dias = estadofechatime / (1000 * 60 * 60 * 24);
		dias = Math.floor(dias);
		var horas = estadofechatime / (1000 * 60 * 60);
		horas = Math.floor(horas % 24);
		var minutos = estadofechatime / (1000 * 60);
		minutos = Math.floor(minutos % 60);
		//-------------------Configurar el Estado con el tiempo actual
		var text = "";
		var textTranscurrido = "";
		switch (estadofecha) {
			case "Pendiente":
				text = "Inicia en ";
				break;

			case "Proceso":
				text = "Finaliza en ";
				textTranscurrido = "Inicio hace ";
				break;

			case "Termino":
				text = "Terminó hace ";
				break;
		}
		var textFecha = "";
		if (dias) {
			if (dias > 1) {
				textFecha += dias + " dias ";
			} else {
				textFecha += dias + " dia ";
			}
		}
		if (horas) {
			if (horas > 1) {
				textFecha += horas + " horas ";
			} else {
				textFecha += horas + " hora ";

			}
		}
		if (minutos) {
			if (minutos > 1) {
				textFecha += minutos + " minutos ";
			} else {
				textFecha += minutos + " minuto ";
			}
		} else {
			textFecha += " algunos segundos ";
		}

		var textFechaT = "";
		if (estadofechatimetranscurrido) {
			//-------------------Configurar el Estado con el tiempo transcurrido
			var diasT = estadofechatimetranscurrido / (1000 * 60 * 60 * 24);
			diasT = Math.floor(diasT);
			var horasT = estadofechatimetranscurrido / (1000 * 60 * 60);
			horasT = Math.floor(horasT % 24);
			var minutosT = estadofechatimetranscurrido / (1000 * 60);
			minutosT = Math.floor(minutosT % 60);

			if (diasT) {
				textFechaT += (diasT > 1) ? diasT + " dias " : diasT + " dia ";
			}

			if (horasT) {
				textFechaT += (horasT > 1) ? horasT + " horas " : horasT + " hora ";
			}

			if (minutosT) {
				textFechaT += (minutosT > 1) ? minutosT + " minutos " : minutosT + " minuto ";
			} else {
				textFechaT += " algunos segundos ";
			}
		}
		//-------------------*/
		var obj = {
			ActualObj: fechaActual,
			ActualTime: fechaActualTime,
			Fecha1Obj: fecha1,
			Fecha1Time: fecha1time,
			DuracionMins: duracionMins,
			DuracionMs: duracionms,
			EstadoFecha: estadofecha,
			Texto: text + textFecha,
			TextoTranscurrido: textTranscurrido + textFechaT
		};
		//-------------------
		if (objOptions.byConsole) {
			console.log(obj);
			console.log(objOptions);
			console.groupEnd();
		}
		return obj;
	}

	static FechasDiferencia(Fecha1IN, Fecha2IN) {
		console.group("neoKiri::FechasDiferenciaNow / " + Fecha1IN + " : " + Fecha2IN);
		var fecha1 = neoKiri.FechaStrToObjNK(Fecha1IN);
		var fecha1time = fecha1.Datetime;
		//--------------------------
		var fecha2 = neoKiri.FechaStrToObjNK(Fecha2IN);
		var fecha2time = fecha2.Datetime;
		//--------------------------
		var fechaActual = new Date();
		var fechaActualTime = fechaActual.getTime();
		//-------------------Configurar el Estado con el tiempo actual
		var estadofecha = "";
		var estadofechatime = 0;
		var estadofechatimetranscurrido = 0;
		if (fechaActualTime < fecha1time) {
			estadofecha = "Pendiente";
			estadofechatime = fecha1time - fechaActualTime;
		} else { // De lo contrario si el tiempo actual ya paso al inicio del evento, puede estar en proceso o ya termino otra comprobacion
			if (fechaActualTime < fecha2time) {
				estadofecha = "Proceso";
				estadofechatime = fecha2time - fechaActualTime; //finaliza en
				estadofechatimetranscurrido = fechaActualTime - fecha1time; //Han transcurrido
			} else {
				estadofecha = "Termino";
				estadofechatime = fechaActualTime - fecha2time;
			}
		}
		//-------------------Configurar el Estado con el tiempo actual
		var dias = estadofechatime / (1000 * 60 * 60 * 24);
		dias = Math.floor(dias);
		var horas = estadofechatime / (1000 * 60 * 60);
		horas = Math.floor(horas % 24);
		var minutos = estadofechatime / (1000 * 60);
		minutos = Math.floor(minutos % 60);
		//-------------------Configurar el Estado con el tiempo actual
		var text = "";
		var textTranscurrido = "";
		switch (estadofecha) {
			case "Pendiente":
				text = "Inicia en ";
				break;

			case "Proceso":
				text = "Finaliza en ";
				textTranscurrido = "Inicio hace ";
				break;

			case "Termino":
				text = "Terminó hace ";
				break;
		}
		var textFecha = "";
		if (dias) {
			if (dias > 1) {
				textFecha += dias + " dias ";
			} else {
				textFecha += dias + " dia ";
			}
		}
		if (horas) {
			if (horas > 1) {
				textFecha += horas + " horas ";
			} else {
				textFecha += horas + " hora ";

			}
		}
		if (minutos) {
			if (minutos > 1) {
				textFecha += minutos + " minutos ";
			} else {
				textFecha += minutos + " minuto ";
			}
		} else {
			textFecha += " algunos segundos ";
		}

		var textFechaT = "";
		if (estadofechatimetranscurrido) {
			//-------------------Configurar el Estado con el tiempo transcurrido
			var diasT = estadofechatimetranscurrido / (1000 * 60 * 60 * 24);
			diasT = Math.floor(diasT);
			var horasT = estadofechatimetranscurrido / (1000 * 60 * 60);
			horasT = Math.floor(horasT % 24);
			var minutosT = estadofechatimetranscurrido / (1000 * 60);
			minutosT = Math.floor(minutosT % 60);

			if (diasT) {
				textFechaT += (diasT > 1) ? diasT + " dias " : diasT + " dia ";
			}

			if (horasT) {
				textFechaT += (horasT > 1) ? horasT + " horas " : horasT + " hora ";
			}

			if (minutosT) {
				textFechaT += (minutosT > 1) ? minutosT + " minutos " : minutosT + " minuto ";
			} else {
				textFechaT += " algunos segundos ";
			}
		}
		//-------------------*/
		var obj = {
			ActualObj: fechaActual,
			ActualTime: fechaActualTime,
			Fecha1Obj: fecha1,
			Fecha1Time: fecha1time,
			Fecha2Obj: fecha2,
			Fecha2Time: fecha2time,
			DuracionMins: "duracionMins",
			DuracionMs: "duracionms",
			EstadoFecha: estadofecha,
			Texto: text + textFecha,
			TextoTranscurrido: textTranscurrido + textFechaT
		};
		//-------------------
		console.log(obj);
		console.groupEnd();
		return obj;
	}

	static convertHoraToPM(valor) {
		"use strict";
		console.log("Hora a Convertir: " + valor);

		var arrayHora = valor.split(":");
		arrayHora[0] = Number(arrayHora[0]);
		arrayHora[1] = Number(arrayHora[1]);

		var Hora = arrayHora[0];
		var Minuto = arrayHora[1];
		var Meridian = "a.m.";

		if (Hora === 12) {
			Meridian = "p.m.";

		} else if (Hora === 0) {
			Hora = 12;
		} else if (Hora > 12) {
			Hora = Hora - 12;
			Meridian = "p.m.";
		}

		if (Hora < 10) {
			Hora = String("0" + Hora);
		}

		if (Minuto < 10) {
			Minuto = String("0" + Minuto);
		}
		var resultado = Hora + ":" + Minuto + " " + Meridian;

		console.log(resultado);
		return resultado;
	}

	static html2bbcode(htmlIN, arrayImagesObj) {
		"use strict";
		console.log("HTML a BBcode");
		//console.info(htmlIN);
		var texto = htmlIN;
		texto = texto.replace(/[\r\n]/gm, " ");
		// texto = texto.replace(/\r/gm, "...");
		texto = neoKiri.BBCodeClearHTMLWord(texto);
		texto = texto.replace(/\s{2,}/gm, "");
		//texto = texto.replace(/\\/gm, "");
		//console.log("Caracteres Simples");
		texto = texto.replace(/<\s*(\w+)\s*>/g, "<$1>");
		texto = texto.replace(/<\s*\/\s*(\w+)\s*>/g, "</$1>");
		texto = texto.replace(/<\w+><\/\w+>/g, ""); //Eliminar Lineas Vacias
		//console.log("Saltos de linea y tabulador");
		texto = texto.replace(/\t/g, " ");
		texto = texto.replace(/<br>/g, "[br]");
		//console.log("Estilos en Linea");
		texto = texto.replace(/<a href=\"(https?:\/\/(?:www\.)?[\w]+(?:\.com)(?:\.co)?[\/\w]+)\">([\w]+)<\/a>/g, "[url2=$1]$2[/url2]");
		texto = texto.replace(/<a href=\"(https?:\/\/(?:www\.)?[\w]+(?:\.com)(?:\.co)?[\/\w]+)\" target=\"_blank\">([\w]+)<\/a>/g, "[url2b=$1]$2[/url2b]");
		//console.log("Imagenes");
		texto = texto.replace(/<img src="((?:http:\/\/|https:\/\/)?[\w\.\/\-]+)">/g, "[image=$1]");
		texto = texto.replace(/<img src="((?:http:\/\/|https:\/\/)?[\w\.\/\-]+)" alt="([\w \.,;:\-"_]+)"\/?>/g, "[image=$1]$2[/image]");
		//--ImagenesID
		if (arrayImagesObj) {
			//console.info("Hasta el momento no necesito las imagenes");
		}
		texto = texto.replace(/<img data-nkform-imageid="([\.\-\/\w]+)" src="[\.\-\/\w\:]+">/g, "[imageid=$1]");
		texto = texto.replace(/<iframe data-nkform-videoidyt="(\d+)" src="https?:\/\/www.youtube.com\/embed\/([\w\-]+)"><\/iframe>/g, "[videoidyt=$1]");
		//console.log("NKS");
		texto = texto.replace(/<b>([\w ,;:ÁáÉéÍíÓóÚúÜüÑñ"'“”_\-·\.…\(\)]+)<\/b>/g, "[negrita]$1[/negrita]");
		texto = texto.replace(/<i>([\w ,;:ÁáÉéÍíÓóÚúÜüÑñ"'“”_\-·\.…\(\)]+)<\/i>/g, "[kursiva]$1[/kursiva]");
		texto = texto.replace(/<u>([\w ,;:ÁáÉéÍíÓóÚúÜüÑñ"'“”_\-·\.…\(\)]+)<\/u>/g, "[subrayado]$1[/subrayado]");
		//console.log("NKS con proppiedades");
		texto = texto.replace(/<b>([\w\[\]\/ ,;:ÁáÉéÍíÓóÚúÜüÑñ"'“”_\-·\.…\(\)\=]+)<\/b>/g, "[negrita]$1[/negrita]");
		texto = texto.replace(/<i>([\w\[\]\/ ,;:ÁáÉéÍíÓóÚúÜüÑñ"'“”_\-·\.…\(\)\=]+)<\/i>/g, "[kursiva]$1[/kursiva]");
		texto = texto.replace(/<u>([\w\[\]\/ ,;:ÁáÉéÍíÓóÚúÜüÑñ"'“”_\-·\.…\(\)\=]+)<\/u>/g, "[subrayado]$1[/subrayado]");
		texto = texto.replace(/<h2>([\w ,;:ÁáÉéÍíÓóÚúÜüÑñ"'“”\-\.…]+)<\/h2>/g, "[heading2]$1[/heading2]");
		//console.log("Parrafo");
		texto = texto.replace(/<p>([\w\[\\\/\]=%& ÁáÉéÍíÓóÚúÜüÑñ\.…\-·,;:"“”'_\(\)¿?\¡\!$#\“\”\*–|°´]+)<\/p>/gm, "[parrafo]$1[/parrafo]");
		//console.log("Eliminar Etiquetas Nulas");
		texto = texto.replace(/<div>/gm, "<p>"); //Eliminar Todas las etiquietas no especificadas
		texto = texto.replace(/<\/div>/gm, "</p>"); //Eliminar Todas las etiquietas no especificadas
		//texto = texto.replace(/<\/?\w+>/gm, "");//Eliminar Todas las etiquietas no especificadas
		return texto;
	}

	static bbcode2html(bbIN, arrayImagesObj, arrayVideosIn, designMode, dirRaiz) {
		"use strict";
		console.group("BBcode a HTML::");
		arrayVideosIn = arrayVideosIn || [];
		//console.log(dirRaiz);
		//console.info(bbIN);
		var texto = bbIN || "";
		//texto = texto.replace(/\r\n/gm, "<br/>");
		texto = texto.replace(/\t/g, "[t]");
		texto = texto.replace(/\[url2=(https?:\/\/(?:www\.)?[\w]+(?:\.com)(?:\.co)?[\/\w]+)]([\w]+)\[\/url2]/g, '<a href="$1">$2</a>');
		texto = texto.replace(/\[url2b=(https?:\/\/(?:www\.)?[\w]+(?:\.com)(?:\.co)?[\/\w]+)]([\w]+)\[\/url2b]/g, '<a href="$1" target=\"_blank\">$2</a>');
		//console.log("Imagenes");
		texto = texto.replace(/\[image=((?:http:\/\/|https:\/\/)?[\w\.\/\-]+)]([\w \.,;:\-"_]+)\[\/image]/g, '<img src="$1" alt="$2"/>');
		texto = texto.replace(/\[image=((?:http:\/\/|https:\/\/)?[\w\.\/\-]+)]/g, '<img src="$1"/>');
		//--Imagenes ID
		if (arrayImagesObj) {
			//console.log("Imagenes ID");
			//console.log(arrayImagesObj);
			var matchImages = texto.match(/\[imageid=([\w\-]+\.?(?:png|jpg)?)\]/g);
			if (matchImages) {
				//console.group("Si se econtrarnon imagenes id en bbcode");
				//console.info(matchImages);
				//console.info(arrayImagesObj);
				for (var i = 0; i < matchImages.length; i++) {
					//console.group("Match: vuelta " + (i + 1) + "/" + matchImages.length);
					var match = matchImages[i];
					var id = match.replace(/\[imageid=([\w\-]+\.?(?:png|jpg)?)\]/g, "$1");
					//console.log("match: " + match + " - id: " + id);
					for (var j = 0; j < arrayImagesObj.length; j++) {
						//console.group("SearchId ArrayImages");
						//console.log(arrayImagesObj[j].id_image);
						//console.log(id);
						//console.info("Datatype arrayimageid: " + typeof arrayImagesObj[j].id_image);
						//console.info("Datatype idMatcheado: " + typeof id);
						var idtoString = (typeof arrayImagesObj[j].id_image === "number") ? arrayImagesObj[j].id_image.toString() : arrayImagesObj[j].id_image;
						if (id === idtoString) {
							//console.log("si encontre el id, designMode: " + designMode);
							if (designMode === true) {
								texto = texto.replace(match, '<img data-nkform-imageid="' + arrayImagesObj[j].id_image + '" src="' + dirRaiz + arrayImagesObj[j].SrcH + '">');
							} else {
								var nnPicture = document.createElement("picture");
								var nnPictureH = document.createElement("source");
								nnPictureH.srcset = dirRaiz + arrayImagesObj[j].SrcH;
								nnPictureH.media = "(min-width: 700px)";
								nnPicture.appendChild(nnPictureH);
								var nnPictureM = document.createElement("source");
								nnPictureM.srcset = dirRaiz + arrayImagesObj[j].SrcM;
								nnPictureM.media = "(min-width: 400px) and (max-width: 699px)";
								nnPicture.appendChild(nnPictureM);
								var nnPictureS = document.createElement("source");
								nnPictureS.srcset = dirRaiz + arrayImagesObj[j].SrcS;
								nnPictureS.media = "(max-width: 399px)";
								nnPicture.appendChild(nnPictureS);
								var nnPictureImg = document.createElement("img");
								nnPictureImg.src = dirRaiz + arrayImagesObj[j].SrcH;
								nnPictureImg.alt = arrayImagesObj[j].Caption;
								nnPictureImg.title = arrayImagesObj[j].Caption;
								nnPicture.appendChild(nnPictureImg);
								//texto = texto.replace(match, '<img src="' + arrayImagesObj[j].SrcH + '" alt="' + arrayImagesObj[j].Caption + '" title="' + arrayImagesObj[j].Caption + '" >');
								texto = texto.replace(match, nnPicture.outerHTML);
							}
							//console.groupEnd();
							break;
						} else {
							//console.warn("nop encontre el id");
						}
						//console.groupEnd();
					}
					//console.groupEnd();
				}
				//console.groupEnd();
			}
		}
		texto = texto.replace(/\[imageid=\d+]/g, ''); //Eliminar rastros de imageid si no se encuentra ninguna
		//--VideosYT ID
		//console.log("VideosYT ID");
		if (arrayVideosIn.length) {
			var matchVideos = texto.match(/\[videoidyt=([\w\-]+)\]/gm);
			if (matchVideos) {
				//console.group("Si hay VideosYT id");
				//console.info(matchVideos);
				//console.info(arrayVideosIn);
				for (var iV = 0; iV < matchVideos.length; iV++) {
					//console.group("Match: vuelta " + (iV + 1) + "/" + matchVideos.length);
					var matchV = matchVideos[iV];
					var idV = matchV.replace(/\[videoidyt=([\w\-]+)\]/g, "$1");
					//console.log("match: " + matchV + " - id: " + idV);
					for (var jV = 0; jV < arrayVideosIn.length; jV++) {
						//console.group("SearchId ArrayVideos");
						//console.log(arrayVideosIn[jV].id_video);
						//console.log(idV);
						//console.info("Datatype arrayVideoid: " + typeof arrayVideosIn[jV].id_video);
						//console.info("Datatype idMatcheado: " + typeof idV);
						var idtoStringV = (typeof arrayVideosIn[jV].VideoID === "number") ? arrayVideosIn[jV].VideoID.toString() : arrayVideosIn[jV].VideoID;
						if (idV === idtoStringV) {
							//console.log("si encontre el id, designMode: " + designMode);
							texto = texto.replace(matchV, '<iframe data-nkform-videoidyt="' + arrayVideosIn[jV].VideoID + '" src="https://www.youtube.com/embed/' + arrayVideosIn[jV].Src_ID + '"></iframe>');
						} else {
							//console.warn("nop encontre el id");
						}
						//console.groupEnd();
					}
					//console.groupEnd();
				}
				//console.groupEnd();
			}
		}
		texto = texto.replace(/\[videoidyt=([\w\-]+)]/g, ''); //Eliminar rastros de videos youtube si no se encuentra ninguna
		//----------
		//texto=texto.replace(/\[imageid=([\.\w\-\/]+)]/g, '<img data-nkform-imageid="$1" src="$1">');
		//console.log("N,K,S");
		texto = texto.replace(/\[negrita]([\w ÁáÉéÍíÓóÚúÜüÑñ\.…,;:\-·"'“”_\(\)]+)\[\/negrita]/g, "<b>$1</b>");
		texto = texto.replace(/\[kursiva]([\w ÁáÉéÍíÓóÚúÜüÑñ\.…,;:\-·"'“”_\(\)]+)\[\/kursiva]/g, "<i>$1</i>");
		texto = texto.replace(/\[subrayado]([\w ÁáÉéÍíÓóÚúÜüÑñ\.…,;:\-·"'“”_\(\)]+)\[\/subrayado]/g, "<u>$1</u>");
		//texto = texto.replace(/\[br]/g, "<br/>");
		//console.log("N,K,S con atributos dentro");
		texto = texto.replace(/\[negrita]([\w<\/> ÁáÉéÍíÓóÚúÜüÑñ\.…,;:\-·"'“”_\(\)]+)\[\/negrita]/g, "<b>$1</b>");
		texto = texto.replace(/\[kursiva]([\w<\/> ÁáÉéÍíÓóÚúÜüÑñ\.…,;:\-·"'“”_\(\)]+)\[\/kursiva]/g, "<i>$1</i>");
		texto = texto.replace(/\[subrayado]([\w<\/> ÁáÉéÍíÓóÚúÜüÑñ\.…,;:\-·"'“”_\(\)]+)\[\/subrayado]/g, "<u>$1</u>");
		texto = texto.replace(/\[heading2]([\w ,;:ÁáÉéÍíÓóÚúÜüÑñ"'“”\-·\.…]+)\[\/heading2]/g, "<h2>$1</h2>");
		//console.log("parrafo");
		texto = texto.replace(/\[parrafo]([\w<\\\/>=#$%& ÁáÉéÍíÓóÚúÜüÑñ\.…,;:\-·"'“”_\()¿\?¡!\*“”–|°´]+)\[\/parrafo]/g, "<p>$1</p>");
		console.groupEnd();
		return texto;
	}

	static BBCodeClearHTMLWord(HTMLin) {
		console.log("clearHTMLWord");
		var htmlParsed = HTMLin;
		//htmlParsed = htmlParsed.replace(/\r\n/gm, "nl");
		//htmlParsed = htmlParsed.replace(/\r?\n/gm, "nl");
		htmlParsed = htmlParsed.replace(/<!--[\W\w]+-->/g, "");
		htmlParsed = htmlParsed.replace(/style=\"[\w\-:\.;%&\s(,)]+\"/g, "");
		htmlParsed = htmlParsed.replace(/class=\"Mso\w+\"/g, "");
		htmlParsed = htmlParsed.replace(/lang=\"\w\w\"/g, "");
		htmlParsed = htmlParsed.replace(/lang=\"\w\w\-\w\w\"/g, "");
		htmlParsed = htmlParsed.replace(/align=\"\w+\"/g, "");
		htmlParsed = htmlParsed.replace(/&nbsp;/g, "");
		htmlParsed = htmlParsed.replace(/<\s*\/?span\s*>/gm, "");
		htmlParsed = htmlParsed.replace(/P\s/gm, "");
		htmlParsed = htmlParsed.replace(/<\/?o:p>/gm, "");
		htmlParsed = htmlParsed.replace(/(<a name=\"[\w]+\">)(<\/a>)/gm, "");
		htmlParsed = htmlParsed.replace(/(<a name=\"[\w]+\">)(.+)(<\/a>)/gm, "$2");
		htmlParsed = htmlParsed.replace(/style=\".+\"/gm, "");
		return htmlParsed;
	}

	static formatSizeUnits(bytes) {
		if (bytes >= 1073741824) {
			bytes = (bytes / 1073741824).toFixed(2) + " GB";
		} else if (bytes >= 1048576) {
			bytes = (bytes / 1048576).toFixed(2) + " MB";
		} else if (bytes >= 1024) {
			bytes = (bytes / 1024).toFixed(2) + " KB";
		} else if (bytes > 1) {
			bytes = bytes + " bytes";
		} else if (bytes == 1) {
			bytes = bytes + " byte";
		} else {
			bytes = "0 bytes";
		}
		return bytes;
	}

	static jxIDSFixEnviar(ArrayIds, ArrayMsg, urlAction, node) {

	}

	static MapsOpenLayers(nodeIn, Lat, Long, Zoom, Titulo, Descripcion) {
		nodeIn.innerHTML = "";
		var _this=this;
		//var Lat=6.04338;
        //var Long=-74.99449;
        //var Zoombutton;
        var nnBoton = document.createElement("button");
		nnBoton.innerHTML="Mostrar Mapa";
        nodeIn.appendChild(nnBoton);
        var nnBotonOcultar = document.createElement("button");
		nnBotonOcultar.innerHTML="Ocultar Mapa";
		nnBotonOcultar.style.display="none";
        nodeIn.appendChild(nnBotonOcultar);
        var nnMapaContenedor = document.createElement("div");
        nodeIn.appendChild(nnMapaContenedor);
        nnMapaContenedor.style.display = "none";
		var Mapa=null;
		nnBoton.onclick=function() {
			nnBoton.style.display="none";
			nnBotonOcultar.style.display="block";
			nnMapaContenedor.width = "100%";
			nnMapaContenedor.style.width = "100%";
			nnMapaContenedor.height = "80vh";
			nnMapaContenedor.style.height = "80vh";
			nnMapaContenedor.style.display = "block";
			var map = L.map(nnMapaContenedor);
			Mapa=map;            
			map.setView([Lat, Long], Zoom);
			
			L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
				maxZoom: 18
			}).addTo(map);
			L.control.scale().addTo(map);
			//map.locate();
			
			var marker = L.marker([Lat, Long]).addTo(map);
		};
		nnBotonOcultar.onclick=function() {
			nnBoton.style.display="block";
			nnBotonOcultar.style.display="none";
			Mapa.remove();
			nnMapaContenedor.removeAttribute("width");
			nnMapaContenedor.removeAttribute("height");
			nnMapaContenedor.removeAttribute("style");
			nnMapaContenedor.style.display="none";
		};



	}
	static MapsGoogle(nodeIn, Lat, Long, Zoom, Titulo, Descripcion) {
		nodeIn.innerHTML = "";
		nodeIn.className = "MapsGoogleNK";
		nodeIn.style.height = "80hv";
		// The location of Uluru
		var Coordenadas = {
			lat: Lat, //-25.344,
			lng: Long //131.031
		};
		// The map, centered at Uluru
		var map = new google.maps.Map(nodeIn, {
			zoom: Zoom,
			center: Coordenadas,
		});
		// The marker, positioned at Uluru
		var marker = new google.maps.Marker({
			position: Coordenadas,
			map: map,
		});
	}
}