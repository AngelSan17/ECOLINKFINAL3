package me.sandria.ecolink

import Evento
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.appcompat.widget.SearchView
import androidx.fragment.app.Fragment
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.google.android.material.floatingactionbutton.FloatingActionButton

class EventsFragment : Fragment(), EventosAdapter.OnEventoClickListener {

    private lateinit var recyclerView: RecyclerView
    private lateinit var eventosAdapter: EventosAdapter
    private lateinit var searchView: SearchView
    private lateinit var fabAddEvent: FloatingActionButton

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        // Infla el layout para este fragmento
        return inflater.inflate(R.layout.activity_events_fragment, container, false)
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        // Configurar RecyclerView
        recyclerView = view.findViewById(R.id.events_recycler_view)
        recyclerView.layoutManager = LinearLayoutManager(context)
        eventosAdapter = EventosAdapter(mutableListOf(), this)
        recyclerView.adapter = eventosAdapter

        // Configurar SearchView
        searchView = view.findViewById(R.id.search_view)
        searchView.setOnQueryTextListener(object : SearchView.OnQueryTextListener {
            override fun onQueryTextSubmit(query: String?): Boolean {
                // Puede ser útil si quieres buscar cuando el usuario presiona "buscar" en el teclado
                query?.let { eventosAdapter.filter(it) }
                return true
            }

            override fun onQueryTextChange(newText: String?): Boolean {
                // Filtrar a medida que el usuario escribe
                newText?.let { eventosAdapter.filter(it) }
                return true
            }
        })

        // Configurar FloatingActionButton



        // Cargar eventos inicialmente
        loadEvents()
    }

    private fun loadEvents() {
        val eventosDePrueba = listOf(
            Evento("Evento 1", "playa", "10:00", "Ruben", "https://static8.depositphotos.com/1010338/959/i/950/depositphotos_9598735-stock-photo-two-beach-chairs-on-idyllic.jpg"),
            Evento("Playa", "rio", "11:00", "Angel", "https://a.cdn-hotels.com/gdcs/production126/d714/782bc102-ab95-4169-a194-eeee6d6260df.jpg?impolicy=fcrop&w=1600&h=1066&q=medium"),
            Evento("Rio", "rio", "12:00", "Sofia", "https://cdn0.geoenciclopedia.com/es/posts/7/0/0/rios_7_600.webp"),
            Evento("Comunidad ", "comunidad", "13:00", "Alex ", "https://i0.wp.com/www.vertigos.mx/wp-content/uploads/2023/10/Se-realiza-recorrido-por-la-localidad-para-iniciar-con-actividades-de-limpieza-2023.jpeg?w=420&ssl=1"),
            Evento("Municipio", "Municipio", "14:00", "Carlitos", "https://www.ejemplos.co/wp-content/uploads/2016/04/uso-sustentable-planeta-min-e1475305901766.jpg"),
            Evento("Basurero", "Basurero", "15:00", "Encargado 6", "https://ichef.bbci.co.uk/news/800/cpsprodpb/A255/production/_109675514_gettyimages-1166817364-2.jpg"),
            Evento("Evento 7", "Valle", "16:00", "Encargado 7", "urlImagen7")
        )
        eventosAdapter.updateEventosList(eventosDePrueba)
    }

    private fun showCreateEventDialog() {
        // Implementa la lógica para mostrar un diálogo de creación de evento
    }

    override fun onEventoClicked(evento: Evento) {
        // Aquí manejas lo que pasa cuando un evento es clickeado, por ejemplo, mostrar detalles
        showEventDetails(evento)
    }

    private fun showEventDetails(evento: Evento) {
        // Implementa la lógica para mostrar los detalles de un evento, por ejemplo abrir un nuevo Fragment
    }
}
