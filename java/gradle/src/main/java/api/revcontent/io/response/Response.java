package api.revcontent.io.response;

import com.fasterxml.jackson.annotation.JsonCreator;
import com.fasterxml.jackson.annotation.JsonProperty;
import com.fasterxml.jackson.annotation.JsonRootName;

@JsonRootName("data")
public class Response {
    public Boolean success;
    private Boost[] data;

    @JsonCreator
    public Response(@JsonProperty("data") final Boost[] boosts) {
        this.data = boosts;
    }

    @JsonProperty("data")
    public Boost[] getData() {
        return data;
    }
}

