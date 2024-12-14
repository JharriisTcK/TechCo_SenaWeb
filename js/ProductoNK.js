/*jshint esversion: 6 */
class ProductoNK {
	constructor(nodeIN, urlAction, KeyJX, dirRaiz, objOptionsIn) {
		"use strict";
		var _this = this;
		this.urlAction = urlAction||"NO DEFINIDO";
		this.KeyJX = KeyJX||"";
		this.dirRaiz = dirRaiz || "";
        //--------------------------------------
        this.nodeProducto = nodeIN || document.createElement("div");
        this.nodeProducto.innerHTML = "";
        this.nodeProducto.className = "ProductoNK";
		//---------------------------------
		objOptionsIn = objOptionsIn || {};
		this.objOption = {
            nodePortada: objOptionsIn.nodePortada || document.createElement("div"),
            loadingImg: objOptionsIn.loadingImg || "img/loading.gif"
		};
		
		this.loadingImg=document.createElement("img");
		this.loadingImg.loading="lazy";
		this.loadingImg.encoding="async";
		this.loadingImg.src=this.dirRaiz + this.objOption.loadingImg;

		this.InCarrito=0;
		this.EsFavorito=false;
		this.CarritoCantidadSeleccionada=1;
        this.valueProducto = {};
		//------------------------------------
		this.nodeHeader = document.createElement("div");
		this.nodeHeader.className = "ProductoNK_HeaderBox";
		this.nodeProducto.appendChild(this.nodeHeader);
		//--------
		this.nodeTitulo = document.createElement("h1");
		this.nodeHeader.appendChild(this.nodeTitulo);
		this.nodeTitulo.className = "TituloBox";
		//--------
		this.nodePrecio = document.createElement("div");
		this.nodePrecio.className = "PrecioBox";
		this.nodeProducto.appendChild(this.nodePrecio);
		//--------
		this.nodeContenido = document.createElement("div");
		this.nodeContenido.className = "ContenidoBox";
		this.nodeProducto.appendChild(this.nodeContenido);
		//--------
		this.nodeFiles = document.createElement("div");
		this.nodeFiles.className = "FilesBox";
		this.nodeProducto.appendChild(this.nodeFiles);
		//--------
		this.nodeImagenes = document.createElement("div");
		this.nodeImagenes.className = "ImagenesBox";
		this.nodeProducto.appendChild(this.nodeImagenes);
		//--------
		this.nodeComentarios = null;
		//--------
		this.nodesFramesYT = [];
		//---------------------------
		this.nodeTitulo.onclick = function () {
			console.clear();
			console.info("Publicacion");
			console.log(_this);
		};
		this.jxProductoGet();
	}

	HeaderConfig() {
		"use strict";
		this.nodeHeader.innerHTML = "";
		//-----------------------------
		//-------SLIDER----------
		var nnHeaderSliderContainer=document.createElement("div");
		nnHeaderSliderContainer.className="SliderContainer";
		this.nodeHeader.appendChild(nnHeaderSliderContainer);
		var picture = document.createElement("picture");
		picture.className = "HeaderPortada";
		nnHeaderSliderContainer.appendChild(picture);
		var img = document.createElement("img");
		img.className = "HeaderPortada";
		picture.appendChild(img);
		var source1 = document.createElement("source");
		source1.media = "(min-width: 700px)";
		picture.appendChild(source1);
		var source2 = document.createElement("source");
		source2.media = "(min-width: 400px) and (max-width: 699px)";
		picture.appendChild(source2);
		var source3 = document.createElement("source");
		source3.media = "(max-width: 399px)";
		picture.appendChild(source3);
		if (this.valueProducto.PortadaH) {
			source1.srcset = this.dirRaiz + this.valueProducto.PortadaH;
			source2.srcset = this.dirRaiz + this.valueProducto.PortadaM;
			source3.srcset = this.dirRaiz + this.valueProducto.PortadaS;
			img.src = this.dirRaiz + this.valueProducto.PortadaH;
			img.alt = this.valueProducto.PortadaC;
			img.title = this.valueProducto.PortadaC;
		} else {
			source1.srcset = this.dirRaiz + "img/PublicacionPortadaH.webp";
			source2.srcset = this.dirRaiz + "img/PublicacionPortadaM.webp";
			source3.srcset = this.dirRaiz + "img/PublicacionPortadaS.webp";
			img.src = this.dirRaiz + "img/PublicacionPortadaH.webp";
			img.alt = "";
			img.title = "";
		}
		//-------INFO -----------
		var nodeInfoHeaderContainer = document.createElement("div");
		this.nodeHeader.appendChild(nodeInfoHeaderContainer);
		// nodeInfoHeaderContainer.className="InfoContainer";
		nodeInfoHeaderContainer.className = "HeaderInfo";
		nodeInfoHeaderContainer.classList.add("cssnk-gradientanimation");
		this.nodeTitulo.innerHTML = this.valueProducto.Nombre;
		nodeInfoHeaderContainer.appendChild(this.nodeTitulo);
		document.title=this.valueProducto.Nombre+" | Producto";
		var nodeDescripcion = document.createElement("p");
		nodeInfoHeaderContainer.appendChild(nodeDescripcion);
		nodeDescripcion.className = "HeaderDescripcion";
		nodeDescripcion.innerHTML = this.valueProducto.Descripcion;
		nodeDescripcion.title = this.valueProducto.Descripcion;
	}

	PrecioConfig() {
		var _this=this;
		this.nodePrecio.innerHTML = "";
		var nnContainerInfo=document.createElement("div");
		nnContainerInfo.className="InfoContainer";
		this.nodePrecio.appendChild(nnContainerInfo);
		var nnContainerPrecio=document.createElement("div");
		nnContainerPrecio.className="PrecioContainer";
		this.nodePrecio.appendChild(nnContainerPrecio);
		var nnContainerStatus=document.createElement("div");
		nnContainerStatus.className="StatusContainer";
		this.nodePrecio.appendChild(nnContainerStatus);

		// -----------------------------

		var nnDisponiblesBox=document.createElement("div");
		nnDisponiblesBox.className="PrecioDisponibles";
		nnContainerInfo.appendChild(nnDisponiblesBox);
		var nodeDisponiblesB = document.createElement("b");
		nodeDisponiblesB.innerHTML="Disponibles: ";
		nnDisponiblesBox.appendChild(nodeDisponiblesB);
		var nodeDisponiblesSpan = document.createElement("span");
		nodeDisponiblesSpan.innerHTML=this.valueProducto.Disponibles;
		nnDisponiblesBox.appendChild(nodeDisponiblesSpan);
		var nodeBR = document.createElement("br");
		nnDisponiblesBox.appendChild(nodeBR);
		var nodePrecioUndB = document.createElement("b");
		nodePrecioUndB.innerHTML="Precio/UND: ";
		nodePrecioUndB.className="PrecioUND";
		nnDisponiblesBox.appendChild(nodePrecioUndB);
		var nodePrecioUndSpan = document.createElement("span");
		nodePrecioUndSpan.innerHTML=ProductoNK.NumeroAMoneda(this.valueProducto.PrecioFinal) + " COP";
		nnDisponiblesBox.appendChild(nodePrecioUndSpan);
		
		var nnCantidadBox=document.createElement("div");
		nnContainerInfo.appendChild(nnCantidadBox);
		var nodeCantidadLabel = document.createElement("label");
		nnCantidadBox.appendChild(nodeCantidadLabel);
		var nodeCantidadB = document.createElement("b");
		nodeCantidadB.innerHTML="Cantidad: ";
		nodeCantidadLabel.appendChild(nodeCantidadB);
		var nodeCantidadInput = document.createElement("input");
		nodeCantidadInput.type="number";
		nodeCantidadInput.value=this.CarritoCantidadSeleccionada;
		nodeCantidadInput.min=1;
		nodeCantidadInput.max=parseInt(this.valueProducto.Disponibles) || 1;
		nodeCantidadInput.placeholder="Cantidad";
		nodeCantidadLabel.appendChild(nodeCantidadInput);

		// -----------------------------
		
		var nnPrecioBox=document.createElement("div");
		nnPrecioBox.className="PrecioTotal";
		nnContainerPrecio.appendChild(nnPrecioBox);
		var nodePrecioB = document.createElement("b");
		nodePrecioB.innerHTML="Total: ";
		nnPrecioBox.appendChild(nodePrecioB);
		var nodePrecioNumero = document.createElement("span");
		var valuePrecio=this.valueProducto.PrecioFinal*this.CarritoCantidadSeleccionada;
		nodePrecioNumero.innerHTML=ProductoNK.NumeroAMoneda(valuePrecio)+" COP";
		nnPrecioBox.appendChild(nodePrecioNumero);
		// --------------------
		if(this.InCarrito) {
			var nnInCarrito=document.createElement("div");
			nnInCarrito.className="InCarrito";
			nnInCarrito.innerHTML=this.CarritoCantidadSeleccionada+" unidades<br/>En carrito de compras";
			nnContainerPrecio.appendChild(nnInCarrito);
			// console.warn("SI esta en carrito");
		} else {
			// console.warn("NO esta en carrito");
		}
		// --------------------
		var nnControlesBox=document.createElement("div");
		nnContainerPrecio.appendChild(nnControlesBox);
		nnControlesBox.className="PrecioControles";
		
		var nodeVacioControl = document.createElement("div");
		nnControlesBox.appendChild(nodeVacioControl);
		//---------
		var nodeFavoritoControl = document.createElement("button");
		nnControlesBox.appendChild(nodeFavoritoControl);
		nodeFavoritoControl.alt="Añadir a Favoritos";
		nodeFavoritoControl.title="Añadir a Favoritos";
		nodeFavoritoControl.className="corazoncontain";
		var nodeFavorito = document.createElement("div");
		nodeFavoritoControl.appendChild(nodeFavorito);
		nodeFavorito.addEventListener("click", function() {
			_this.jxFavoritoClic(nnContainerStatus);
		}, false);
		if(this.EsFavorito) {
			nodeFavorito.className="corazon_rojo";
		} else {
			nodeFavorito.className="corazon";
		}
		//---------
		var nodePrecioCarrito = document.createElement("button");
		nnControlesBox.appendChild(nodePrecioCarrito);
		// nodePrecioCarrito.data=this.dirRaiz+"img/carrito_compras.svg";
		if(this.InCarrito) {
			nodePrecioCarrito.innerHTML="Actualizar Carrito";
		} else {
			nodePrecioCarrito.innerHTML="Añadir al Carrito";
		}
		nodePrecioCarrito.className="ButtonCarrito";
		nodePrecioCarrito.addEventListener("click", function() {
			_this.jxCarritoAdd(nnContainerStatus);
		}, false);
		//------------------------------
		nodeCantidadInput.onchange=function() {
			_this.valueProducto.Disponibles=parseInt(_this.valueProducto.Disponibles);
			var CantidadInput=parseInt(nodeCantidadInput.value);
			if(CantidadInput>_this.valueProducto.Disponibles) {
				nodeCantidadInput.setAttribute("value", _this.valueProducto.Disponibles);
				nodeCantidadInput.value=_this.valueProducto.Disponibles;
				CantidadInput=parseInt(_this.valueProducto.Disponibles);
			}
			if(CantidadInput<1) {
				nodeCantidadInput.setAttribute("value", 1);
				nodeCantidadInput.value=1;
				CantidadInput=parseInt(1);
			}
			_this.CarritoCantidadSeleccionada=CantidadInput;
			var valuePrecio=_this.valueProducto.PrecioFinal*_this.CarritoCantidadSeleccionada;
			nodePrecioNumero.innerHTML=ProductoNK.NumeroAMoneda(valuePrecio)+" COP";
		}

	}

	ContentConfig() {
		this.nodeContenido.innerHTML = "";
		//------------------------------
		var nodeContenidoTitulo = document.createElement("div");
		nodeContenidoTitulo.className = "ProductoNombre";
		nodeContenidoTitulo.innerHTML = this.valueProducto.Nombre;
		this.nodeContenido.appendChild(nodeContenidoTitulo);
		//------------ BreadWalk
		var nodeBreadWalk = document.createElement("div");
		nodeBreadWalk.className = "ProductoCategorias";
		this.nodeContenido.appendChild(nodeBreadWalk);
		var nodeCCategoria_InicioA = document.createElement("a");
		// nodeCCategoria_ProtuctosA.className = "Productos";
		nodeCCategoria_InicioA.innerHTML = "Inicio";
		nodeCCategoria_InicioA.href = this.dirRaiz;
		nodeBreadWalk.appendChild(nodeCCategoria_InicioA);
		var nodeBW_CategoriaA = document.createElement("a");
		nodeBW_CategoriaA.innerHTML = "Categorias";
		nodeBW_CategoriaA.href = this.dirRaiz+"Categorias/";
		nodeBreadWalk.appendChild(nodeBW_CategoriaA);
		var nodeCCategoria_CategoriaA = document.createElement("a");
		// nodeCCategoria_ProtuctosA.className = "Productos";
		nodeCCategoria_CategoriaA.innerHTML = this.valueProducto.CategoriaNombre;
		nodeCCategoria_CategoriaA.href = this.dirRaiz+"Categorias/"+this.valueProducto.CategoriaNickDir+"/";
		nodeBreadWalk.appendChild(nodeCCategoria_CategoriaA);
		//------------------------------
		var nodeParrafosPublicacion = document.createElement("div");
		nodeParrafosPublicacion.className="ProductoParrafos";
		nodeParrafosPublicacion.innerHTML = neoKiri.bbcode2html(this.valueProducto.Contenido, this.valueProducto.Imagenes, this.valueProducto.Videos, false, this.dirRaiz);
		this.nodeContenido.appendChild(nodeParrafosPublicacion);
		//Final de articulo y fecha
		var FechaTexto = neoKiri.FechaStrToObjNK(this.valueProducto.Fecha_Registro);
		var nodeFooter = document.createElement("div");
		nodeFooter.className = "PublicacionFooter";
		var nnVisitas = document.createElement("span");
		nnVisitas.innerHTML = "👀​ " + this.valueProducto.Visitas + " Visitas";
		nodeFooter.appendChild(nnVisitas);
		var nodeDatetime = document.createElement("time");
		nodeDatetime.className = "PublicacionFecha";
		nodeDatetime.innerHTML = "📆 " + FechaTexto.DateNombre;
		nodeFooter.appendChild(nodeDatetime);
		this.nodeContenido.appendChild(nodeFooter);
	}

	framesYTGet() {
		var nodes = this.nodeContenido.querySelectorAll("iframe[data-nkform-videoidyt]");
		this.nodesFramesYT = nodes;
		this.framesYTConfig();
	}

	framesYTConfig() {
		var nodes = this.nodesFramesYT;
		console.group("Publicacion::configFramesYT");
		for (var i = 0; i < nodes.length; i++) {
			nodes[i].width = "640";
			nodes[i].height = "360";
			nodes[i].setAttribute("allowfullscreen", "allowfullscreen");
		}
		console.log(nodes);
		console.groupEnd();

	}

	ArchivosConfig() {
		this.nodeFiles.innerHTML = "";
		if (!this.valueProducto.Files || !this.valueProducto.Files.length) {
			return false;
		}
		var nnTitulo = document.createElement("h2");
		nnTitulo.innerHTML = "Archivos Adjuntos";
		this.nodeFiles.appendChild(nnTitulo);
		var nnList = document.createElement("ul");
		this.nodeFiles.appendChild(nnList);
		for (var i = 0; i < this.valueProducto.Files.length; i++) {
			this.ArchivoConfig(nnList, this.valueProducto.Files[i]);
		}
		var nnClear = document.createElement("span");
		nnClear.className = "clear";
		this.nodeFiles.appendChild(nnClear);
	}
	ArchivoConfig(nodeUl, fileValue_in) {
		/*
		Clicks: 0
		Descripcion: "manual logo"
		File_Name: "20190924Art20190924UNdN_Manual_Creacion_Logo_Ecocagui.pdf"
		File_Size: 1814080
		File_Type: "PDF"
		Name: "Manual Creacion Logo Ecocagui.pdf"
		dirFile: "../../../Publicacion/2019/09/Art20190924UNdN/20190924Art20190924UNdN_Manual_Creacion_Logo_Ecocagui.pdf"
		dirFileAbsolute: "https://www.periodicoelarriero.com.co/Publicacion/2019/09/Art20190924UNdN/20190924Art20190924UNdN_Manual_Creacion_Logo_Ecocagui.pdf"
		id_file: 0
		*/
		var _this = this;
		var nnLi = document.createElement("li");
		nodeUl.appendChild(nnLi);
		var nnLink = document.createElement("a");
		nnLink.target = "_BLANK";
		nnLi.appendChild(nnLink);
		var nnImgIco = document.createElement("img");
		nnLink.appendChild(nnImgIco);
		var nnB = document.createElement("b");
		nnB.innerHTML = fileValue_in.Name;
		nnLink.appendChild(nnB);

		switch (fileValue_in.File_Type) {
			case "pdf":
			case "PDF":
				nnLink.href = "https://docs.google.com/viewer?url=" + fileValue_in.dirFileAbsolute;
				nnImgIco.src = this.dirRaiz + "img/iconPDF.png";
				break;

			default:
				nnLink.href = this.dirRaiz + fileValue_in.dirFile;
				break;
		}
		var nnDescripcion = document.createElement("p");
		nnDescripcion.innerHTML = fileValue_in.Descripcion;
		nnLink.appendChild(nnDescripcion);
		var nnSize = document.createElement("span");
		nnSize.innerHTML = "[" + neoKiri.formatSizeUnits(fileValue_in.File_Size) + "]";
		nnLink.appendChild(nnSize);
	}
	ImagenesConfig() {
		this.nodeImagenes.innerHTML = "";
		var PublicacionImagenesSlider = new SliderImagenesNK1(this.nodeImagenes, this.valueProducto.Imagenes, {}, this.dirRaiz);

	}

	// -------------------------------------------------------
	
	jxProductoGet() {
		this.nodeHeader.innerHTML = "";
        var nnLoadingImg=document.createElement("img");
        nnLoadingImg.loading="lazy";
        nnLoadingImg.encoding="async";
        nnLoadingImg.src=this.dirRaiz+this.objOption.loadingImg;
        this.nodeHeader.appendChild(nnLoadingImg);
		var _this = this;
		var fd = "?ProductoNK=ProductoGet";
		fd+="&KeyJX="+this.KeyJX;
		fd+="&ProductoID="+this.KeyJX;
		fetch(this.urlAction+fd)
			.then(resp => resp.text())
			.then(data => {
				try {
					data=JSON.parse(data);
				} catch (error) {
					console.log(error);
					console.log(data);
					return false;					
				}
				console.info("jxProductoGet");
				console.log(data);
				if (Boolean(data.RespuestaBool)) {
					this.valueProducto = data.Producto;
					this.CarritoCantidadSeleccionada = data.CarritoCantidad;
					this.InCarrito=Boolean(data.CarritoExisteIn);
					this.EsFavorito=Boolean(data.EsFavorito);
				} else {
					console.error(data.RespuestaError);
					return false;
				}
				this.iniciar();
			})
			.catch(err => {
				this.nodeHeader.innerHTML = "Creo que hay un error en el sistema, por favor informanos:<br/>Error: " + err;
				console.error(err);
			});
	}

	jxCarritoAdd(nnStatus, ProductoID) {
		nnStatus.appendChild(this.loadingImg);
		var fd = new FormData();
		fd.append("ProductoNK", "CarritoAdd");
		// fd.append("ProductoID", this.valueProducto.ProductoID);
		fd.append("KeyJX", this.KeyJX);
		fd.append("Cantidad", this.CarritoCantidadSeleccionada);
		fetch(this.urlAction, {
				method: "POST",
				body: fd
			})
			.then(resp => resp.text())
			.then(data => {
				nnStatus.removeChild(this.loadingImg);
				// /*
				console.info("ProductoNK::CarritoAdd()");
				try {
					data=JSON.parse(data);
				} catch (error) {
					
				}
				console.log(data);
				// */
				if (data.RespuestaBool) {
					this.InCarrito=true;
					this.PrecioConfig();
					console.log("Producto añadido al carrito de compras :D.");
					if(globalThis.CarritoComprasNK_Reload) {
						globalThis.CarritoComprasNK_Reload();
					}
				} else {
					console.warn(data.RespuestaError);
					nnStatus.innerHTML="Lo siento, esta caracteristica de carrito de compras esta disponible solo para usuarios registrados.";
				}
			})
			.catch(err => {
				console.error(err);
			});
	}

	jxFavoritoClic(nnStatus) {
		nnStatus.appendChild(this.loadingImg);
		var fd = new FormData();
		fd.append("ProductoNK", "FavoritoClic");
		// fd.append("ProductoID", this.valueProducto.ProductoID);
		fd.append("KeyJX", this.KeyJX);
		fetch(this.urlAction, {
				method: "POST",
				body: fd
			})
		.then(resp => resp.text())
		.then(data => {
			nnStatus.removeChild(this.loadingImg);
			// /*
			console.info("ProductoNK::jxFavoritoClic()");
			try {
				data=JSON.parse(data);
			} catch (error) {
				console.warn(data);
				return false;
			}
			console.log(data);
			// */
			if (data.RespuestaBool) {
				console.log(data.RespuestaOK);
				if(Boolean(data.EsFavorito)) {
					this.EsFavorito=true;
				} else {
					this.EsFavorito=false;
				}
				this.PrecioConfig();
			} else {
				console.warn(data.RespuestaError);
				nnStatus.innerHTML="Lo siento, esta caracteristica esta disponible solo para usuarios registrados.";
			}
		})
		.catch(err => {
			console.error(err);
		});
	}

	iniciar() {
		"use strict";
		var _this = this;
		this.HeaderConfig();
		this.PrecioConfig();
		this.ContentConfig();
		this.ArchivosConfig();
		this.ImagenesConfig();
		this.framesYTGet();
	}

	static NumeroAMoneda(NumeroIn) {
		let precio = NumeroIn;
        let options = { style: 'currency', currency: 'COP', minimumFractionDigits: 0 };
        let precioformateado = precio.toLocaleString('es-CO', options);
		return precioformateado
	}
}
