/*jshint esversion: 6 */
/*
//todos los llamados JX deben responder con un objeto JSON
{
	RespuestaBool: boolean //Si el llamado fue verdadero o falso
	RespuestaError: TEXT //El texto que identique el error
}

//Obtener Imagenes
FileImageNK = ImagenesGet
@return {
	Imagenes[]: {
		id_image: "",
		SrcH:
		SrcS:
		Caption:""
	}
}

//Subir Imagen
FileImageNK = ImagenSubir
Caption = TEXT
Imagen = FILE
@return {
	id_image:
}

//Eliminar Imagen
FileImageNK = ImagenEliminar
KeyJX = TEXT
id_imagen = TEXT 					//imagen a eliminar

*/

class FileImageNK {
    constructor(nodeIn, KeyJX, urlAction, dirRaiz, objOptionsIn) {
		var _this=this;
        this.nodeObj=nodeIn||document.createElement("div");
        this.nodeObj.innerHTML="";
        this.nodeObj.className="FileImageNK";
        //------
        this.KeyJX=KeyJX;
        this.urlAction=urlAction;
        this.dirRaiz=dirRaiz;
		objOptionsIn = objOptionsIn || {};
		this.objOptions = {
			w: objOptionsIn.w || 900,
			h: objOptionsIn.h || 900,
			multiple: objOptionsIn.multiple || false,
			valuesSend: objOptionsIn.valuesSend || [],
			titulo: objOptionsIn.titulo || "FileImageNK",
			valuesIn: objOptionsIn.valuesIn,
			cargarImagenes: objOptionsIn.cargarImagenes || "Si",
			loadingImg: objOptionsIn.loadingImg || "img/loading.gif"
		};
		//---------------
		this.nodeHeader=document.createElement("div");
		this.nodeHeader.className="FileImageNK_Portada";
		this.nodeObj.appendChild(this.nodeHeader);
		this.nodeControls=document.createElement("div");
		this.nodeControls.className="FileImageNK_Control";
		this.nodeObj.appendChild(this.nodeControls);
		this.nodeStatusBox=document.createElement("div");
		this.nodeStatusBox.className="FileImageNK_Stat";
		this.nodeObj.appendChild(this.nodeStatusBox);
		this.nodeImagenesUp=document.createElement("div");
		this.nodeImagenesUp.className="FileImageNK_ImagenesUp";
		this.nodeObj.appendChild(this.nodeImagenesUp);
		this.nodeImagenes=document.createElement("div");
		this.nodeImagenes.className="FileImageNK_Imagenes";
		this.nodeObj.appendChild(this.nodeImagenes);
		//---------------
		this.valueImagenes=[];
		this.imagesCanvas = [];
		this.imagesPreparedFiles = [];
		//---------------
		this.ControlsConfig();
		//-------------
		this.nodeHeader.onclick = function () {
			console.clear();
			console.group("FileImageNK");
			console.log(_this);
			console.groupEnd();
		};
		this.HeaderConfig();
		if(this.objOptions.valuesIn) {
			this.valueImagenes=this.objOptions.valuesIn;
			this.ConfigImagenesActuales();
		} else {
			this.jxImagenesGet();
		}
	}

	HeaderConfig() {
		this.nodeHeader.innerHTML="";
		var nnTitulo=document.createElement("h2");
		this.nodeHeader.appendChild(nnTitulo);
		nnTitulo.innerHTML=this.objOptions.titulo;
	}

	ControlsConfig() {
		this.nodeControls.innerHTML="";
		var _this=this;
		//----
		var nodeSelectInp=document.createElement("div");
		this.nodeControls.appendChild(nodeSelectInp);
		var nnLabel = document.createElement("label");
		nodeSelectInp.appendChild(nnLabel);
		var nnB = document.createElement("b");
		nnB.innerHTML = "🖼️​ Seleccionar Imagen 📸​";
		nnLabel.appendChild(nnB);
		var nnInputFile = document.createElement("input");
		nnLabel.appendChild(nnInputFile);
		nnInputFile.type = "file";
		nnInputFile.setAttribute("accept", "image/*");
		if (this.objOptions.multiple === true) {
			nnInputFile.setAttribute("multiple", true);
		}
		//-------------------------
		nnInputFile.onchange = function (ev) {
			var filesInp = ev.target.files;
			_this.getImageToCompress(filesInp);
			nnInputFile.value = [];
			_this.imagesPreparedFiles = [];
			_this.imagesCanvas = [];
		};
		//-------------------------
		var nodeReloadBox=document.createElement("div");
		this.nodeControls.appendChild(nodeReloadBox);
		var reloadButton = document.createElement("button");
		reloadButton.innerHTML = "🔁​ Recargar Imagenes";
		nodeReloadBox.appendChild(reloadButton);
		reloadButton.onclick=function() {
			_this.jxImagenesGet();
		};
	}

	getImageToCompress (files) {
		for (var i = 0; i < files.length; i++) {
			if (!files[i].type.match("image/*")) {
				continue;
			}
			this.compressImageFile(files[i], files.length, this.objOptions.w, this.objOptions.h);
		}
	}

	compressImageFile (imageFile, length, mxWidth, mxHeight) {
		var _this=this;
		var cnv = document.createElement("canvas");
		var ctx = cnv.getContext("2d");
		var urlImage = URL.createObjectURL(imageFile);
		var image = new Image();
		image.onload = function () {
			_this.imagesPreparedFiles.push(image);
			var w = image.width;
			var h = image.height;
			var wC = w;
			var hC = h;
			var orientation = null;
			if (w >= h) {
				orientation = "horizontal";
			}
			else if (h > w) {
				orientation = "vertical";
			}
			switch (orientation) {
				case "vertical":
					if (h > mxHeight) {
						wC *= mxHeight / h;
						hC = mxHeight;
					}
					break;
				case "horizontal":
					if (w > mxWidth) {
						hC *= mxWidth / w;
						wC = mxWidth;
					}
					break;
			}
			cnv.width = wC; //widthCanvas
			cnv.height = hC; //heightCanvas
			ctx.drawImage(image, 0, 0, wC, hC);
			_this.imagesCanvas.push(cnv);
			//-------------------
			if (_this.imagesCanvas.length >= length) {
				_this.configureDivUpload();
			}
			globalThis.URL.revokeObjectURL(urlImage);
		};
		image.src = urlImage;
	}

	

	configureDivUpload() {
		// var BoxMessage=neoKiri.BoxDivComplete({wPercent: 100, hPercent: 90, Titulo: "Subir Imagenes"});
		// BoxMessage[0].className = "FileImageNK_MaxInner";
		// BoxMessage[1].className = "FileImageNK_MaxOutter";
		var nnDivUploadImagesList=this.nodeImagenesUp;
		nnDivUploadImagesList.innerHTML = "";
		for (var i = 0; i < this.imagesCanvas.length; i++) {
			var nItem = document.createElement("li");
			nnDivUploadImagesList.appendChild(nItem);
			var nPreview = document.createElement("div");
			nPreview.className = "preview";
			nItem.appendChild(nPreview);
			nPreview.appendChild(this.imagesCanvas[i]);
			var niCaption = document.createElement("textarea");
			nItem.appendChild(niCaption);
			nItem.appendChild(document.createElement("br"));
			var ndControls = document.createElement("div");
			ndControls.className = "controls";
			nItem.appendChild(ndControls);
			var nbSubir = document.createElement("button");
			nbSubir.innerHTML = "Subir";
			nbSubir.className = "BotonAmarillo";
			nbSubir.setAttribute("data-formnk_setfileimage", i);
			nbSubir.onclick = this.jxImagenCanvasSubir.bind(this, this.imagesCanvas[i], niCaption, nItem, nbSubir);
			ndControls.appendChild(nbSubir);
			var nbCancel = document.createElement("button");
			nbCancel.innerHTML = "Cancelar";
			nbCancel.className = "BotonRojo";
			nbCancel.onclick = this.deleteImageCanvas.bind(this, nItem);
			ndControls.appendChild(nbCancel);
		}
	}

	deleteImageCanvas(itemLiDelete) {
		var parentNode = itemLiDelete.parentNode;
		parentNode.removeChild(itemLiDelete);
	}

	jxImagenCanvasSubir(canvas, nodeInp, box, button) {
		var _this=this;
		button.disabled=true;
		var subirImagenBlob = function (blob) {
			var text = nodeInp.value;
			var fd = new FormData();
			fd.append("FileImageNK", "ImagenSubir");
			fd.append("KeyJX", _this.KeyJX);
			fd.append("Caption", text);
			fd.append("Imagen", blob);
			for(var i=0; i<_this.objOptions.valuesSend.length; i++) {
				fd.append(
					_this.objOptions.valuesSend[i][0],
					_this.objOptions.valuesSend[i][1]
					);
				}
				var jx = new XMLHttpRequest();
				jx.upload.addEventListener("progress", function (e) {
					if (e.lengthComputable) {
						var loaded = neoKiri.formatSizeUnits(e.loaded);
						var total = neoKiri.formatSizeUnits(e.total);
						var percentage = Math.round((e.loaded * 100) / e.total);
						box.innerHTML = loaded + "/" + total + "[" + percentage + "%]";
					}
			}, false);
			jx.onreadystatechange = function () {
				switch (jx.readyState ) {
					case 4:
						switch(jx.status) {
							case 200: 
							button.disabled=false;
							console.group("FileImageNK::jxImagenCanvasSubir() - "+_this.objOptions.titulo);
							var respuesta = jx.responseText;
							try {
								respuesta = JSON.parse(respuesta);
							} catch (error) {
								
							}
							console.log(respuesta);
							console.groupEnd();
							if (!respuesta.RespuestaBool) {
								return false;
							}
							box.parentNode.removeChild(box);
							_this.jxImagenesGet();
							break;

							case 404:
								
							break;
						}
					break;
				}
			};
			jx.open("post", _this.urlAction, true);
			jx.send(fd);
		};
		var DataImageURL = canvas.toBlob(subirImagenBlob);
		console.groupEnd();
	}

	ConfigImagenesActuales() {
		this.nodeImagenes.innerHTML = "";
		var list = document.createElement("ul");
		list.className = "FileImage_boxImageReady_listReady";
		this.nodeImagenes.appendChild(list);
		//--Configurar Nodos
		if (this.valueImagenes.length) {
			for (var i = 0; i < this.valueImagenes.length; i++) {
				this.ConfigImagenActual(i, list);
			}
		}
		else {
			list.style.display = "none";
			var liZero = document.createElement("li");
			liZero.innerHTML = "No hay elementos";
			list.appendChild(liZero);
		}
	}

	ConfigImagenActual(Iterator, list) {
		var valueImagen=this.valueImagenes[Iterator];
		var _this=this;
		var nLi = document.createElement("li");
		nLi.className="ImagenActualItem";
		list.appendChild(nLi);
		var nPreview = document.createElement("div");
		nPreview.className = "Preview";
		nLi.appendChild(nPreview);
		var nImage = new Image();
		nImage.encoding = "async";
		nImage.loading = "lazy";
		nImage.src = this.dirRaiz + valueImagen.SrcS;
		nImage.alt = valueImagen.Caption;
		nImage.title = valueImagen.Caption;
		nPreview.appendChild(nImage);
		var nnStatus = document.createElement("div");
		nnStatus.className="Status";
		nLi.appendChild(nnStatus);
		var nText = document.createElement("p");
		nText.innerHTML = valueImagen.Caption;
		nLi.appendChild(nText);
		var nControls = document.createElement("div");
		nControls.className = "ContolesBox";
		nLi.appendChild(nControls);
		//----Vincular Funciones
		nImage.onclick=function() {
			neoKiri.ImgMaximize(_this.dirRaiz+valueImagen.SrcH, valueImagen.Caption);
		};
		//----Vincular Funciones de Identificacion
		if (valueImagen.id_image) {
			var nbDelete = document.createElement("button");
			nbDelete.innerHTML = "🗑️​ Eliminar​";
			nbDelete.className = "BotonRojo";
			nbDelete.setAttribute("title", "Eliminar");
			nbDelete.onclick = this.jxImagenEliminar.bind(this, valueImagen.id_image, nLi);
			nControls.appendChild(nbDelete);
		}
		//----Nuevos ELementos del objeto
		valueImagen.nnLi=nLi;
		valueImagen.nnStatus=nnStatus;
	}

	jxImagenesGet() {
		if(this.objOptions.cargarImagenes!="Si") {
			return false;
		}
		var _this=this;
		//-----------
		this.nodeStatusBox.innerHTML="";
		var nnImageLoading=document.createElement("img");
		nnImageLoading.loading="lazy";
		nnImageLoading.encoding="async";
		nnImageLoading.src=this.dirRaiz+this.objOptions.loadingImg;
		this.nodeStatusBox.appendChild(nnImageLoading);
		//-----------
		var fd = new FormData();
		fd.append("FileImageNK", "ImagenesGet");
		fd.append("KeyJX", this.KeyJX);
		for(var i=0; i<this.objOptions.valuesSend.length; i++) {
			fd.append(
				this.objOptions.valuesSend[i][0], 
				this.objOptions.valuesSend[i][1]
			);
		}
		//-------------
		fetch(this.urlAction, {method:"POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
			console.group("FileImageNK::jxImagenesGet() - "+this.objOptions.titulo);
			try {
				data=JSON.parse(data);
			} catch (error) {
				console.log(error);
				console.log(data);
				return false;
			}
			console.log(data);
			console.groupEnd();
			this.nodeStatusBox.innerHTML="";
			if(data.RespuestaBool) {
				_this.valueImagenes=data.Imagenes;
			} else {
				_this.valueImagenes = [];
			}
			this.HeaderConfig();
			this.ConfigImagenesActuales();
		})
		.catch(error=>{
			console.warn(error);
		});
	}

	jxImagenEliminar (idImageActual, liContainerImage) {
		if (!idImageActual) {
			console.warn("No hay id de imagen a eliminar");
			return false;
		}
		var fd = new FormData();
		fd.append("FileImageNK", "ImagenEliminar");
		fd.append("KeyJX", this.KeyJX);
		fd.append("id_imagen", idImageActual);
		for(var i=0; i<this.objOptions.valuesSend.length; i++) {
			fd.append(
				this.objOptions.valuesSend[i][0], 
				this.objOptions.valuesSend[i][1]
				);
		}
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
			console.group("FileImageNK::jxImagenEliminar() - "+this.objOptions.titulo);
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
				var parent = liContainerImage.parentNode;
				parent.removeChild(liContainerImage);
				//this.jxImagenesGet();
			}
		})
		.catch(error=>{
			console.warn(error);
		});
	}
}