<?php

/**
 * @nowelium
 */
class HermitEvent {

    const EVT_INIT = 0x0;
    const EVT_SETUP = 0x1;
//    const EVT_INIT_END = 0x7;
    
    const EVT_READ = 0x8;
    const EVT_SELECT = 0x9;
//    const EVT_READ_END = 0xf;

    const EVT_WRITE = 0x10;
    const EVT_INSERT = 0x11;
    const EVT_UPDATE = 0x12;
    const EVT_DELETE = 0x13;
//    const EVT_WRITE_END = 0x17;
    
    const EVT_PROCEDURE = 0x18;
//    const EVT_PROCEDURE_END = 0x1f;

    const UNKNOWN = -1;
    const MASK = 0x78;

    public static function isInit($evt){
        return self::EVT_INIT === ($evt & self::MASK);
    }
    public static function isRead($evt){
        return self::EVT_READ === ($evt & self::MASK);
    }
    public static function isWrite($evt){
        return self::EVT_WRITE === ($evt & self::MASK);
    }
}
