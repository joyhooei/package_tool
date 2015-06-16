LOCAL_PATH := $(call my-dir)
SUPPORT_PATH := ../../../support
CLASSES_PATH := $(LOCAL_PATH)/../../../Classes
SDK_PATH := ../../SDK/yunrongtianshang/anzhi
MAIN_SRC := ../../jni/ddtank
INCLUDE_SUPPORT_PATH := $(LOCAL_PATH)/../../../support
INCLUDE_SDK_PATH := $(LOCAL_PATH)/../../SDK/yunrongtianshang/anzhi
INCLUDE_COCOS2DX_PATH := $(LOCAL_PATH)/../../../../cocos2d-x



include $(CLEAR_VARS)  
LOCAL_MODULE := libedittextutil
LOCAL_SRC_FILES := $(SDK_PATH)/so/libedittextutil.so         
include $(PREBUILT_SHARED_LIBRARY)

include $(CLEAR_VARS)  
LOCAL_MODULE := libentryex
LOCAL_SRC_FILES := $(SDK_PATH)/so/libentryex.so         
include $(PREBUILT_SHARED_LIBRARY)

include $(CLEAR_VARS)  
LOCAL_MODULE := libUnionPay
LOCAL_SRC_FILES := $(SDK_PATH)/so/libUnionPay.so         
include $(PREBUILT_SHARED_LIBRARY)


include $(CLEAR_VARS)

LOCAL_MODULE := game_shared
LOCAL_CPPFLAGS += -fexceptions
LOCAL_CPPFLAGS += -fpermissive
LOCAL_CPPFLAGS += -DSDK_ANZHI
LOCAL_CPPFLAGS += -DQUDAO__YUN_RONG_TIAN_SHANG
LOCAL_MODULE_FILENAME := libgame

LOCAL_SRC_FILES := $(MAIN_SRC)/src/main.cpp
LOCAL_SRC_FILES += $(SUPPORT_PATH)/SDK/CSdkForLua.cpp
LOCAL_SRC_FILES += $(SUPPORT_PATH)/SDK/MySdk/MySdkForLua.cpp
LOCAL_SRC_FILES += $(SUPPORT_PATH)/SDK/MySdk/CMyGameSdk.cpp
LOCAL_SRC_FILES += $(SUPPORT_PATH)/script_support/LuaSupport.cpp
LOCAL_SRC_FILES += $(SDK_PATH)/cpp/CGameSdk.cpp
LOCAL_SRC_FILES += $(SDK_PATH)/cpp/ANZHISdkForLua.cpp
LOCAL_SRC_FILES += $(SDK_PATH)/cpp/JniCallback.cpp


LOCAL_C_INCLUDES := $(INCLUDE_SUPPORT_PATH)/
LOCAL_C_INCLUDES += $(INCLUDE_SUPPORT_PATH)/script_support
LOCAL_C_INCLUDES += $(INCLUDE_SUPPORT_PATH)/platform/android
LOCAL_C_INCLUDES += $(INCLUDE_SUPPORT_PATH)/SDK
LOCAL_C_INCLUDES += $(INCLUDE_SDK_PATH)/cpp
LOCAL_C_INCLUDES += $(LOCAL_PATH)/../../../../third_party/jsoncpp/include
LOCAL_C_INCLUDES += $(INCLUDE_COCOS2DX_PATH)/cocos2dx
LOCAL_C_INCLUDES += $(INCLUDE_COCOS2DX_PATH)/cocos2dx/include
LOCAL_C_INCLUDES += $(INCLUDE_COCOS2DX_PATH)/cocos2dx/platform
LOCAL_C_INCLUDES += $(INCLUDE_COCOS2DX_PATH)/cocos2dx/platform/android
LOCAL_C_INCLUDES += $(INCLUDE_COCOS2DX_PATH)/cocos2dx/kazmath/include
LOCAL_C_INCLUDES += $(INCLUDE_COCOS2DX_PATH)/scripting/lua/lua-5.1.5
LOCAL_C_INCLUDES += $(INCLUDE_COCOS2DX_PATH)/scripting/lua/tolua
LOCAL_C_INCLUDES += $(INCLUDE_COCOS2DX_PATH)/CocosDenshion/include
LOCAL_C_INCLUDES += $(INCLUDE_COCOS2DX_PATH)/cocos2dx/platform/android/jni

LOCAL_C_INCLUDES += $(INCLUDE_COCOS2DX_PATH)/extensions
LOCAL_C_INCLUDES += $(INCLUDE_COCOS2DX_PATH)/extensions/CCBReader
LOCAL_C_INCLUDES += $(INCLUDE_COCOS2DX_PATH)/extensions/GUI/CCControlExtension
LOCAL_C_INCLUDES += $(INCLUDE_COCOS2DX_PATH)/extensions/GUI/CCScrollView
LOCAL_C_INCLUDES += $(INCLUDE_COCOS2DX_PATH)/extensions/network
LOCAL_C_INCLUDES += $(INCLUDE_COCOS2DX_PATH)/extensions/LocalStorage 

AppDelegate := $(filter %AppDelegate.cpp, $(wildcard $(CLASSES_PATH)/*))
#$(if $(AppDelegate),$(eval LOCAL_SHARED_LIBRARIES += libddtank),$(eval include $(PREBUILT_SHARED_LIBRARY)))

LOCAL_WHOLE_STATIC_LIBRARIES := json_static
LOCAL_SHARED_LIBRARIES := libddtank
PRODUCT_COPY_FILES += libs/armeabi/libddtank.so:libddtank/libddtank.so

include $(BUILD_SHARED_LIBRARY)

$(call import-module,third_party/jsoncpp)
$(if $(AppDelegate),$(call import-module,jni/ddtank),$(call import-module,libddtank))
$(call import-module,jni/ddtank)