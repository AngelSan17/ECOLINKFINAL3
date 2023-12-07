package me.sandria.ecolink

import UserFragment
import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import androidx.fragment.app.Fragment
import androidx.viewpager2.widget.ViewPager2
import com.google.android.material.tabs.TabLayout
import com.google.android.material.tabs.TabLayoutMediator


class CommunityActivity : AppCompatActivity() {

    private lateinit var viewPager: ViewPager2
    private lateinit var tabs: TabLayout

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_community)

        viewPager = findViewById(R.id.view_pager)
        tabs = findViewById(R.id.tabs)

        val adapter = object : SectionsPagerAdapter (this) {
            override fun getItemCount(): Int = 3
            override fun createFragment(position: Int): Fragment {
                return when (position) {
                    0 -> CommunityFragment() // Fragmento para la comunidad
                    1 -> EventsFragment() // Fragmento para eventos
                    2 -> UserFragment() // Fragmento para el perfil del usuario
                    else -> Fragment()
                }
            }

        }
        viewPager.adapter = adapter

        TabLayoutMediator(tabs, viewPager) { tab, position ->
            tab.text = when (position) {
                0 -> "Comunidad"
                1 -> "Eventos"
                2 -> "Usuario"
                else -> null
            }
        }.attach()
    }
}

