package com.victorycodedev.plugins.toastkit.bridge

import androidx.fragment.app.FragmentActivity
import com.nativephp.mobile.utils.NativeActionCoordinator
import org.json.JSONObject
import java.lang.ref.WeakReference

internal object ToastKitEventDispatcher {
    private const val SHOWN = "Victorycodedev\\ToastKit\\Events\\ToastShown"
    private const val DISMISSED = "Victorycodedev\\ToastKit\\Events\\ToastDismissed"
    private const val ACTION = "Victorycodedev\\ToastKit\\Events\\ToastActionPressed"
    private const val PRESSED = "Victorycodedev\\ToastKit\\Events\\ToastPressed"
    private var activity = WeakReference<FragmentActivity>(null)

    fun bind(value: FragmentActivity) { activity = WeakReference(value) }
    fun shown(id: String) = send(SHOWN, JSONObject().put("toastId", id))
    fun dismissed(id: String, reason: String) = send(DISMISSED, JSONObject().put("toastId", id).put("reason", reason))
    fun action(id: String, actionId: String, data: Map<String, Any>) = send(ACTION, JSONObject().put("toastId", id).put("actionId", actionId).put("data", JSONObject(data)))
    fun pressed(id: String, data: Map<String, Any>) = send(PRESSED, JSONObject().put("toastId", id).put("data", JSONObject(data)))

    private fun send(event: String, payload: JSONObject) {
        activity.get()?.let { NativeActionCoordinator.dispatchEvent(it, event, payload.toString()) }
    }
}
