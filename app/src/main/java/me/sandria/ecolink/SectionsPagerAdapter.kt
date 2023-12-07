package me.sandria.ecolink

import UserFragment
import androidx.fragment.app.Fragment
import androidx.fragment.app.FragmentActivity
import androidx.viewpager2.adapter.FragmentStateAdapter

open class SectionsPagerAdapter(fa: FragmentActivity) : FragmentStateAdapter(fa) {
    private val TAB_TITLES = arrayOf(
        R.string.tab_text_1, // Comunidad
        R.string.tab_text_2, // Eventos
        R.string.tab_text_3  // Usuario
    )

    override fun getItemCount(): Int = TAB_TITLES.size

    override fun createFragment(position: Int): Fragment {
        return when (position) {
            0 -> CommunityFragment()
            1 -> EventsFragment()
            2 -> UserFragment()
            else -> throw IllegalStateException("Position not valid")
        }
    }
}
