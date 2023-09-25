package com.example.mahisha.garbagecollector;


public class Task { // task create to keep the values from the server
    private int roundid;
    private String path;
    private String date;
    private String status;

    public Task(String path, int roundid, String date, String status) {
        this.path = path;
        this.roundid = roundid;
        this.date = date;
        this.status = status;
    }

    public int getRoundid() {
        return roundid;
    }

    public void setRoundid(int roundid) {
        this.roundid = roundid;
    }

    public String getPath() {
        return path;
    }

    public void setPath(String path) {
        this.path = path;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }
}
