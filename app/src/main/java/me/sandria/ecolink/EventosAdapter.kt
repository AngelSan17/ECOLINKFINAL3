package me.sandria.ecolink

import Evento
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.squareup.picasso.Picasso

class EventosAdapter(
    private var eventosList: MutableList<Evento>,
    private val listener: OnEventoClickListener
) : RecyclerView.Adapter<EventosAdapter.EventoViewHolder>() {

    // Se usa para el filtro, para tener siempre la lista original completa
    private var eventosListFull: List<Evento> = ArrayList(eventosList)

    interface OnEventoClickListener {
        fun onEventoClicked(evento: Evento)
    }

    inner class EventoViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        private val nombreEvento: TextView = itemView.findViewById(R.id.nombre_evento)
        private val tipoEvento: TextView = itemView.findViewById(R.id.tipo_evento)
        private val horaEvento: TextView = itemView.findViewById(R.id.hora_evento)
        private val encargadoEvento: TextView = itemView.findViewById(R.id.encargado_evento)
        private val imagenEvento: ImageView = itemView.findViewById(R.id.imagen_evento)

        fun bind(evento: Evento) {
            nombreEvento.text = evento.nombre
            tipoEvento.text = evento.tipo
            horaEvento.text = evento.hora
            encargadoEvento.text = evento.encargado
            if (evento.imagenUrl.isNotEmpty()) {
                Picasso.get().load(evento.imagenUrl).into(imagenEvento)
            } else {
                imagenEvento.setImageResource(R.mipmap.ic_launcher) // Asume que tienes este recurso en tu proyecto
            }
            itemView.setOnClickListener { listener.onEventoClicked(evento) }
        }
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): EventoViewHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.item_evento, parent, false)
        return EventoViewHolder(view)
    }

    override fun onBindViewHolder(holder: EventoViewHolder, position: Int) {
        val evento = eventosList[position]
        holder.bind(evento)
    }

    override fun getItemCount() = eventosList.size

    fun filter(query: String) {
        val filteredList = if (query.isEmpty()) {
            eventosListFull
        } else {
            eventosListFull.filter {
                it.nombre.contains(query, ignoreCase = true) || it.tipo.contains(query, ignoreCase = true)
                // Añade más condiciones de filtrado si es necesario
            }
        }
        eventosList.clear()
        eventosList.addAll(filteredList)
        notifyDataSetChanged()
    }

    fun updateEventosList(eventos: List<Evento>) {
        eventosListFull = ArrayList(eventos) // Actualiza la lista completa para el filtro
        this.eventosList.clear()
        this.eventosList.addAll(eventos)
        notifyDataSetChanged()
    }
}
