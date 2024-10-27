/*jshint esversion: 6 */


/**
 * 
 *  Obj Values In {
 *  	SrcH
 *  	SrcM
 *  	SrcS
 *  	SrcT
 *  	Caption
 * }
 */
class SliderImagenesNK2 {
	constructor(nodeObj, ImagenesIn, objOptionsIn, dirRaiz) {
		"use strict";
		var _this = this;
		this.nodeObj = nodeObj || document.createElement("div");
		this.nodeObj.innerHTML = "";
		this.nodeObj.classList.add("SliderImagenesNK1");
		objOptionsIn = objOptionsIn || {};
		this.dirRaiz = dirRaiz || "";
		//---------------
		this.objOptions = {
			actual: objOptionsIn.actual || 0,
			centerControls: objOptionsIn.centerControls || true,
			arrayNodeLi: [],
		};
		//---------------
		if (!ImagenesIn.length) {
			console.warn("No hay imagenes para el Slider1");
			return false;
		}
		this.imagenesArrObj = ImagenesIn;
		//-----------------------------------------------
		this.ImagenSeleccionadaActual = 0;
		this.ImagenConfiguradaActual = 0;
		this.Maximizado = false;
		this.infoMaximize = {
			wW: globalThis.innerWidth,
			wH: globalThis.innerHeight,
		};

		//----Contenido del slider
		this.nodeHeader = document.createElement("div");
		this.nodeHeader.className = "Header";
		this.nodeObj.appendChild(this.nodeHeader);
		this.nodePreviewBox = document.createElement("div");
		this.nodePreviewBox.className = "PreviewBox";
		this.nodeObj.appendChild(this.nodePreviewBox);
		this.nodeList = document.createElement("ul");
		this.nodeList.className = "Lista";
		this.nodeObj.appendChild(this.nodeList);
		//----Caja contenedora del maximizado
		this.boxMaximize = document.createElement("div");
		this.boxMaximize.className = "SliderImagenesNK1_Max";
		this.boxMaximize_Preview = document.createElement("div");
		this.boxMaximize_Preview.className = "PreviewMax";
		this.boxMaximize.appendChild(this.boxMaximize_Preview);
		this.nnMaximize_Header = document.createElement("div");
		this.nnMaximize_Header.className = "HeaderMax";
		this.boxMaximize.appendChild(this.nnMaximize_Header);
		this.nnMaximize_Caption = document.createElement("p");
		this.nnMaximize_Caption.className = "CaptionMax";
		this.boxMaximize.appendChild(this.nnMaximize_Caption);
		//----
		// this.nodeMaximizePicture.className = "nK_Slider1_maximizeImage";
		//--------------------------------------------------
		this.ImagenesListConfig();
		this.CrearPreviewBox();
		this.CrearMaximizeBox();
		this.Config();
		//--------------------------------------------------
		globalThis.addEventListener("resize", this.AjustarMaximizeBox.bind(this), false);
		globalThis.addEventListener("scroll", this.AjustarMaximizeBox.bind(this), false);
		globalThis.addEventListener("keyup", this.detectarTeclado.bind(this), false);
		//--------------------------------------------------
		this.touchmax_id = 0;
		this.touchmax_ini = [0, 0];
		this.touchmax_fin = [0, 0];
		this.boxMaximize.addEventListener("touchstart", this.detectarTouch_s.bind(this), false);
		this.nodePreviewBox.addEventListener("touchstart", this.detectarTouch_s.bind(this), false);
		//globalThis.addEventListener("touchmove", this.detectarTouch_m.bind(this), false);
		this.boxMaximize.addEventListener("touchend", this.detectarTouch_e.bind(this), false);
		this.nodePreviewBox.addEventListener("touchend", this.detectarTouch_e.bind(this), false);
	}



	ImagenesListConfig() {
		var _this = this;
		this.nodeList.innerHTML = "";
		if (this.imagenesArrObj.length) {
			for (var i = 0; i < this.imagenesArrObj.length; i++) {
				this.ImagenesListConfigItem(this.imagenesArrObj[i], i);
			}
		}
	}
	ImagenesListConfigItem(ImagenObj, IntervalItem) {
		var _this = this;
		var nLi = document.createElement("li");
		this.nodeList.appendChild(nLi);
		ImagenObj.nodeLi = nLi;
		var nliPreview = document.createElement("div");
		nLi.appendChild(nliPreview);
		var nliImage = document.createElement("img");
		nliImage.src = this.dirRaiz + ImagenObj.SrcT;
		nliImage.alt = ImagenObj.Caption;
		nliImage.title = ImagenObj.Caption;
		nliImage.loading = "lazy";
		nliPreview.appendChild(nliImage);
		nLi.addEventListener("click", function () {
			_this.goTo(IntervalItem);
			_this.Maximizar();
			nLi.classList.add("Activo");
		}, false);
	}

	goTo(cual) {
		this.objOptions.actual = parseInt(cual);
		this.ImagenSeleccionadaActual = parseInt(cual);
		console.log("Ir a " + this.ImagenSeleccionadaActual);
		this.Config();
	}

	goNext() {
		var ac = this.ImagenSeleccionadaActual;
		var n = ac + 1;
		if (n >= this.imagenesArrObj.length) {
			this.goTo(0);
		} else {
			this.goTo(n);
		}
	}

	goPrev() {
		var ac = this.ImagenSeleccionadaActual;
		var n = ac - 1;
		if (n < 0) {
			this.goTo(this.imagenesArrObj.length - 1);
		} else {
			this.goTo(n);
		}
	}

	CrearPreviewBox() {
		var _this = this;
		this.nodeNext = document.createElement("button");
		this.nodeNext.className = "ButtonNext";
		this.nodeNext.innerHTML = ">";
		this.nodePreviewBox.appendChild(this.nodeNext);
		this.nodeNext.onclick = function () {
			_this.goNext();
		};

		this.nodePrev = document.createElement("button");
		this.nodePrev.className = "ButtonPrev";
		this.nodePrev.innerHTML = "<";
		this.nodePreviewBox.appendChild(this.nodePrev);
		this.nodePrev.onclick = function () {
			_this.goPrev();
		};
	}

	CrearMaximizeBox() {
		var _this = this;
		//----Header Maximize
		var MaximizeInfo = document.createElement("div");
		MaximizeInfo.className = "Info";
		this.nnMaximize_Header.appendChild(MaximizeInfo);
		var MaximizeClose = document.createElement("div");
		MaximizeClose.innerHTML = "❌​";
		MaximizeClose.className = "Close";
		this.nnMaximize_Header.appendChild(MaximizeClose);

		var nnMaximizeNext = document.createElement("button");
		nnMaximizeNext.innerHTML = ">";
		nnMaximizeNext.className = "ButtonNext";
		this.boxMaximize_Preview.appendChild(nnMaximizeNext);
		var nnMaximizePrev = document.createElement("button");
		nnMaximizePrev.innerHTML = "<";
		nnMaximizePrev.className = "ButtonPrev";
		this.boxMaximize_Preview.appendChild(nnMaximizePrev);
		//---
		nnMaximizeNext.onclick = function () {
			_this.goNext();
		};
		nnMaximizePrev.onclick = function () {
			_this.goPrev();
		};
		MaximizeClose.onclick = function () {
			_this.closeMaximizePreview();
		};
	}

	Config() {
		this.Config_RemoveChilds();
		this.AjustarMaximizeBox();
		//nodo Picture de la imagen
		var _this = this;
		var ImagenObj = this.imagenesArrObj[this.ImagenSeleccionadaActual];
		if (ImagenObj.Picture) {
			this.nodePreviewBox.appendChild(ImagenObj.Picture);
		} else {
			var nodePicture = document.createElement("picture");
			ImagenObj.Picture = nodePicture;
			var sourceH = document.createElement("source");
			sourceH.srcset = this.dirRaiz + this.imagenesArrObj[this.ImagenSeleccionadaActual].SrcH;
			sourceH.media = "(min-width: 700px)";
			nodePicture.appendChild(sourceH);
			var sourceM = document.createElement("source");
			sourceM.srcset = this.dirRaiz + this.imagenesArrObj[this.ImagenSeleccionadaActual].SrcM;
			sourceM.media = "(min-width: 400px) and (max-width: 699px)";
			nodePicture.appendChild(sourceM);
			var sourceS = document.createElement("source");
			sourceS.srcset = this.dirRaiz + this.imagenesArrObj[this.ImagenSeleccionadaActual].SrcS;
			sourceS.media = "(max-width: 399px)";
			nodePicture.appendChild(sourceS);
			var sourceD = document.createElement("img");
			sourceD.src = this.dirRaiz + this.imagenesArrObj[this.ImagenSeleccionadaActual].SrcH;
			sourceD.alt = this.imagenesArrObj[this.ImagenSeleccionadaActual].Caption;
			sourceD.title = this.imagenesArrObj[this.ImagenSeleccionadaActual].Caption;
			nodePicture.appendChild(sourceD);
			sourceD.onload = function () {
				//console.log("Picture Cargada");
				_this.Config();
			};
		}
		//Configurar el Maximizado
		if (this.Maximizado) {
			if (ImagenObj.Imagen) {
				this.boxMaximize_Preview.appendChild(ImagenObj.Imagen);
			} else {
				//El Nodo Imagen
				var nnImagen = document.createElement("img");
				nnImagen.src = this.dirRaiz + this.imagenesArrObj[this.ImagenSeleccionadaActual].SrcH;
				nnImagen.alt = this.imagenesArrObj[this.ImagenSeleccionadaActual].Caption;
				nnImagen.title = this.imagenesArrObj[this.ImagenSeleccionadaActual].Caption;
				ImagenObj.Imagen = nnImagen;
				nnImagen.onload = function () {
					//console.log("Imagen Cargada");
					ImagenObj.ImagenW = nnImagen.width;
					ImagenObj.ImagenH = nnImagen.height;
					_this.Config();
				};
			}
		}
		//----------
		this.nnMaximize_Caption.innerHTML = this.imagenesArrObj[this.ImagenSeleccionadaActual].Caption;
		this.ImagenConfiguradaActual = this.ImagenSeleccionadaActual;
		this.AjustarMaximizeBox();
	}

	Config_RemoveChilds() {
		for (var i = 0; i < this.imagenesArrObj.length; i++) {
			if (this.imagenesArrObj[i].Picture) {
				if (this.imagenesArrObj[i].Picture.parentNode == this.nodePreviewBox) {
					this.nodePreviewBox.removeChild(this.imagenesArrObj[i].Picture);
				}
			}
			if (this.imagenesArrObj[i].Imagen) {
				if (this.imagenesArrObj[i].Imagen.parentNode == this.boxMaximize_Preview) {
					this.boxMaximize_Preview.removeChild(this.imagenesArrObj[i].Imagen);
				}
			}
		}
	}

	AjustarMaximizeBox() {
		if (!this.Maximizado) {
			return false;
		}
		var wW = globalThis.innerWidth;
		var wH = globalThis.innerHeight;
		this.boxMaximize.style.display = "block";
		this.boxMaximize.style.position = "absolute";
		this.boxMaximize.style.width = wW + "px";
		this.boxMaximize.style.height = wH + "px";
		this.boxMaximize.style.top = (globalThis.scrollY) + "px";
		this.boxMaximize.style.left = (globalThis.scrollX) + "px";
		this.boxMaximize.style.overflow = "hidden";
		this.boxMaximize.style.zIndex = 100;
		
		this.boxMaximize_Preview.style.width = wW + "px";
		this.boxMaximize_Preview.style.height = wH + "px";

		this.AjustarMaximize_Imagen();
	}

	Maximizar() {
		var _this = this;
		//Ocultar los scroll del body html
		document.body.style.width = globalThis.innerWidth + "px";
		document.body.style.height = globalThis.innerHeight + "px";
		document.body.style.overflow = "hidden";
		//Obtener primer nodo e insertar maximize
		var firstChild = document.body.firstChild;
		document.body.insertBefore(this.boxMaximize, firstChild);
		this.Maximizado = true;
		this.Config();
		//---------------
		//console.log("history");
		//console.log(history);
		history.pushState(null, null, location.href);
		//console.log(history);
		globalThis.onpopstate = function () {
			if(!_this.Maximizado) {
				return false;
			}
			//console.log("history On Pop");
			//console.log(history);
			history.go(1);
			_this.closeMaximizePreview();
		};
	} //Fin maximizePreview


	AjustarMaximize_Imagen() {
		var ObjImagen = this.imagenesArrObj[this.ImagenConfiguradaActual];
		if (!ObjImagen.Imagen) {
			return false;
		}
		var Imagen = ObjImagen.Imagen;
		Imagen.style.position = "absolute";
		var wImg = ObjImagen.ImagenW;
		var hImg = ObjImagen.ImagenH;
		var orImg = null;
		if (wImg >= hImg) {
			orImg = "horizontal";
		} else if (hImg > wImg) {
			orImg = "vertical";
		} else {
			return false;
		}

		console.log("IMG=" + wImg + ":" + hImg + "(" + orImg + ")");
		//----------------------------------------------
		if (!Imagen.parentNode) {
			return false;
		}
		var wBox = Imagen.parentNode.offsetWidth; //widthBox
		var hBox = Imagen.parentNode.offsetHeight;

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
					Imagen.style.left = (wBox / 2) - (case1W / 2) + "px";
					Imagen.style.top = (hBox / 2) - (case1H / 2) + "px";
					Imagen.style.width = case1W + "px";
					Imagen.style.height = case1H + "px";
				} else {
					Imagen.style.left = (wBox / 2) - (case2W / 2) + "px";
					Imagen.style.top = (hBox / 2) - (case2H / 2) + "px";
					Imagen.style.width = case2W + "px";
					Imagen.style.height = case2H + "px";
				}
				break;
			case "vertical":
				console.info("Box Vertical");
				if (hBox < case1H) {
					Imagen.style.top = (hBox / 2) - (case2H / 2) + "px";
					Imagen.style.left = (wBox / 2) - (case2W / 2) + "px";
					Imagen.style.width = case2W + "px";
					Imagen.style.height = case2H + "px";
				} else {
					Imagen.style.top = (hBox / 2) - (case1H / 2) + "px";
					Imagen.style.left = (wBox / 2) - (case1W / 2) + "px";
					Imagen.style.width = case1W + "px";
					Imagen.style.height = case1H + "px";
				}
				break;
			default:
				console.warn("QUe paho");
				return false;
		} //fin switch orBox
		console.groupEnd();
	}

	closeMaximizePreview() {
		var _this = this;
		if(this.boxMaximize.parentNode!=document.body) {
			return false;
		}
		document.body.removeChild(this.boxMaximize);
		document.body.style.width = "auto";
		document.body.style.height = "auto";
		document.body.style.overflow = "auto";
		document.body.removeAttribute("style");
		this.Maximizado = false;
	}

	centerControlsNextPrev() {
		var hN = this.nodeNext.offsetHeight;
		var hB = this.nodePreview.offsetHeight;
		this.nodeNext.style.top = ((hB / 2) - (hN / 2)) + "px";
		this.nodePrev.style.top = ((hB / 2) - (hN / 2)) + "px";
	}

	detectarTeclado(e) {
		//console.log("teclado pulsado");
		//console.log(e);
		if (e.keyCode == 39) {
			//console.log("ir a >");
			this.goNext();
		}
		if (e.keyCode == 37) {
			//console.log("ir a <");
			this.goPrev();
		}
	}

	detectarTouch_s(e) {
		//console.warn("Touch Start: ");
		//console.log(e);
		//Comprobamos si hay varios eventos del mismo tipo
		if (e.targetTouches.length != 1) {
			return false;
		}
		var touch = e.targetTouches[0];
		this.touchmax_id = touch.identifier;
		this.touchmax_ini = [touch.clientX, touch.clientY];
		//console.log(this.touchmax_ini);
	}
	detectarTouch_m(e) {
		//console.warn("Touch Move: ");
		//console.log(e);
		//Comprobamos si hay varios eventos del mismo tipo
		if (e.targetTouches.length != 1) {
			return false;
		}
	}
	detectarTouch_e(e) {
		//console.warn("Touch End: ");
		//console.log(e);
		//Comprobamos si hay varios eventos del mismo tipo
		if (e.changedTouches.length != 1) {
			return false;
		}
		var touch = e.changedTouches[0];
		if (touch.identifier != this.touchmax_id) {
			return false;
		}
		this.touchmax_fin = [touch.clientX, touch.clientY];
		//console.log(this.touchmax_fin);

		var dir = "";
		var dist = 0;
		if (this.touchmax_ini[0] < this.touchmax_fin[0]) {
			//console.log("Movi a la derecha");
			dir = "derecha";
			dist = this.touchmax_fin[0] - this.touchmax_ini[0];
		} else {
			//console.log("Movi a la izquierda");
			dir = "izquierda";
			dist = this.touchmax_ini[0] - this.touchmax_fin[0];
		}
		console.log(dist);
		if (dist > 100) {
			switch (dir) {
				case "derecha":
					this.goNext();
					break;

				case "izquierda":
					this.goPrev();

					break;

				default:
					console.warn("wtf: touch Slider");
					break;
			}
		}
		this.touchmax_id = 0;
		this.touchmax_ini = [0, 0];
		this.touchmax_fin = [0, 0];
	}

}