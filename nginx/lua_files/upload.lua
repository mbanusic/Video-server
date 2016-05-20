ngx.header.content_type = 'text/plain'

local resty_sha1 = require "resty.sha1"
local upload = require "resty.upload"
local cjson = require "cjson"

local chunk_size = 4096
local form, err = upload:new(chunk_size)
if not form then
    --TODO: better error handling
    ngx.log(ngx.ERR, "failed to new upload: ", err)
    ngx.exit(500)
end
local sha1 = resty_sha1:new()
local file
local file_name
local out = {}

form:set_timeout(1000) -- 1 sec

function my_get_file_name(res, i)
    if not i then
        i = 1
    end
    if not res[i] then
        return
    end
    local filename = ngx.re.match(res[i],'(.+)filename="(.+)"(.*)')
    if filename then
        -- TODO: better storage path handling
        local file = string.clean(filename[2])
        out.name = filename[2];
        return '/var/www/originals/app/' .. file
    else
        return my_get_file_name(res, i+1)
    end
end

function string.clean(str)
    str = str:gsub("%s+", "-")
    str = str:gsub("č", "c")
    str = str:gsub("ć", "c")
    str = str:gsub("š", "s")
    str = str:gsub("ž", "z")
    str = str:gsub("đ", "d")
    return str
end

while true do
    local typ, res, err = form:read()
    if not typ then
        out.error = 'No form'
        ngx.say(cjson.encode(out));
        return
    end

    if typ == "header" then
        if not file_name then
            file_name = my_get_file_name(res)
        end
        if file_name then
            file = io.open(file_name, "w+")
            out.url = file_name
            if not file then
                out.error = 'Cannot create file'
                ngx.say(cjson.encode({files={ out }}))
                return
            end
        end

    elseif typ == "body" then
        if file then
            file:write(res)
            sha1:update(res)
        end

    elseif typ == "part_end" then
        file:close()
        file = nil
        local sha1_sum = sha1:final()
        sha1:reset()
        out.sha = sha1_sum
        out = { files={out} }
        ngx.say(cjson.encode(out))

    elseif typ == "eof" then
        break

    else
        -- do nothing
    end
end
